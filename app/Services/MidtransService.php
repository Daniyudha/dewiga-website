<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = (string) (config('services.midtrans.server_key') ?? '');
        $this->isProduction = (bool) (config('services.midtrans.is_production') ?? false);
    }

    public function createTransaction(array $payload): array
    {
        $apiUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $response = Http::withBasicAuth($this->serverKey, '')
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post($apiUrl, $payload);

        if ($response->failed()) {
            Log::error('Midtrans API Error:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal membuat pembayaran Midtrans: ' . $response->json('error_messages.0', 'Unknown error'),
            ];
        }

        return [
            'success' => true,
            'data' => $response->json(),
        ];
    }

    public function getTransactionStatus(string $orderId): array
    {
        $baseUrl = $this->isProduction
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';

        $response = Http::withBasicAuth($this->serverKey, '')
            ->withHeaders(['Accept' => 'application/json'])
            ->get("{$baseUrl}/{$orderId}/status");

        if ($response->failed()) {
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan status transaksi: ' . $response->json('status_message', 'Unknown error'),
            ];
        }

        return [
            'success' => true,
            'data' => $response->json(),
        ];
    }

    public function buildItemDetails($schedule, $amount = null): array
    {
        $title = $schedule->title ?? $schedule->activity_title ?? 'Paket Wisata';
        $qty = max(1, (int) ($schedule->people_count ?? 1));
        $totalAmount = (int) round((float) ($amount ?? $schedule->amount ?? 0));

        // When an explicit amount is passed (e.g. down payment / uang muka),
        // always send a single item priced exactly at that amount so the sum
        // always matches gross_amount and the Snap page shows one clear line.
        if ($amount !== null) {
            return [
                [
                    'id' => $schedule->schedule_code ?? 'schedule-' . $schedule->id,
                    'price' => max(1, $totalAmount),
                    'quantity' => 1,
                    'name' => mb_substr($title . ' (Pembayaran)', 0, 50),
                ],
            ];
        }

        // Full package price: if it divides evenly per person, show per-person
        // price with quantity = people_count; otherwise a single line item.
        if ($qty > 1 && $totalAmount > 0 && $totalAmount % $qty === 0) {
            return [
                [
                    'id' => $schedule->schedule_code ?? 'schedule-' . $schedule->id,
                    'price' => (int) ($totalAmount / $qty),
                    'quantity' => $qty,
                    'name' => mb_substr($title, 0, 50),
                ],
            ];
        }

        return [
            [
                'id' => $schedule->schedule_code ?? 'schedule-' . $schedule->id,
                'price' => max(1, $totalAmount),
                'quantity' => 1,
                'name' => mb_substr($title, 0, 50),
            ],
        ];
    }

    public function buildCustomerDetails($schedule): array
    {
        $name = $schedule->institution ?? $schedule->customer_name ?? 'Customer';

        return [
            'first_name' => mb_substr((string) $name, 0, 50),
            'last_name' => '',
            'email' => $schedule->email ?? 'customer@example.com',
            'phone' => $schedule->phone ?? $schedule->number_phone ?? '',
        ];
    }

    public function isConfigured(): bool
    {
        return !empty($this->serverKey);
    }
}