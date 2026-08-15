<?php

namespace App\Http\Controllers;

use App\Models\SchedulePayment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    /**
     * Handle Midtrans webhook notification.
     *
     * @param Request $request
     * @param MidtransService $midtrans
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, MidtransService $midtrans)
    {
        Log::info('Midtrans Webhook received:', $request->all());

        $orderId = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');
        $paymentType = $request->input('payment_type');
        $grossAmount = $request->input('gross_amount');

        if (empty($orderId)) {
            return response()->json(['status' => 'error', 'message' => 'order_id is required'], 400);
        }

        // Find the payment record by midtrans_order_id
        $payment = SchedulePayment::where('midtrans_order_id', $orderId)->first();

        if (!$payment) {
            Log::warning('Midtrans Webhook: Payment not found for order_id: ' . $orderId);
            return response()->json(['status' => 'not_found', 'message' => 'Payment not found'], 404);
        }

        // Update payment based on transaction status
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus === 'accept') {
                    $payment->update([
                        'status' => 'paid',
                        'reference_number' => $request->input('transaction_id') ?? $payment->reference_number,
                    ]);
                } elseif ($fraudStatus === 'challenge') {
                    $payment->update(['status' => 'challenge']);
                }
                break;

            case 'settlement':
                $payment->update([
                    'status' => 'paid',
                    'reference_number' => $request->input('transaction_id') ?? $payment->reference_number,
                ]);
                break;

            case 'pending':
                $payment->update(['status' => 'pending']);
                break;

            case 'deny':
            case 'cancel':
            case 'expire':
                $payment->update(['status' => 'failed']);
                break;

            default:
                Log::warning('Midtrans Webhook: Unhandled transaction status: ' . $transactionStatus);
                break;
        }

        // Optionally verify with Midtrans API
        try {
            $verification = $midtrans->getTransactionStatus($orderId);
            if ($verification['success']) {
                $serverStatus = $verification['data']['transaction_status'] ?? null;
                Log::info('Midtrans Webhook verification for ' . $orderId . ': ' . $serverStatus);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook verification failed: ' . $e->getMessage());
        }

        return response()->json(['status' => 'ok']);
    }
}