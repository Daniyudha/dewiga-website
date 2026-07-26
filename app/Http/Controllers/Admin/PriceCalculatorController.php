<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePriceEstimationRequest;
use App\Models\PriceEstimation;
use App\Services\PriceCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriceCalculatorController extends Controller
{
    protected PriceCalculatorService $calculatorService;

    public function __construct(PriceCalculatorService $calculatorService)
    {
        $this->calculatorService = $calculatorService;
    }

    /**
     * Display the price calculator page (history table only).
     */
    public function index(Request $request)
    {
        $query = PriceEstimation::with('createdBy');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('estimation_number', 'like', "%{$search}%")
                  ->orWhere('institution_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        $estimations = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.price-calculator.index', compact('estimations'));
    }

    /**
     * Show the create estimation form.
     */
    public function create()
    {
        return view('admin.price-calculator.create');
    }

    /**
     * Calculate estimation (AJAX or store).
     */
    public function calculate(StorePriceEstimationRequest $request)
    {
        $data = $request->validated();
        // Ensure activity participants is at least student count if not set
        if (empty($data['activity_participant_count']) && !empty($data['student_count'])) {
            $data['activity_participant_count'] = (int) $data['student_count'];
        }
        $result = $this->calculatorService->calculate($data);

        return response()->json($result);
    }

    /**
     * Store a new estimation.
     */
    public function store(StorePriceEstimationRequest $request)
    {
        try {
            $data = $request->validated();
            // Ensure activity participants is at least student count if not set
            if (empty($data['activity_participant_count']) && !empty($data['student_count'])) {
                $data['activity_participant_count'] = (int) $data['student_count'];
            }
            $estimation = $this->calculatorService->save($data);

            return redirect()
                ->route('admin.price-calculator.show', $estimation)
                ->with('success', 'Estimasi harga berhasil disimpan. Nomor estimasi: ' . $estimation->estimation_number);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan estimasi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified estimation.
     */
    public function show(PriceEstimation $priceEstimation)
    {
        $priceEstimation->load('items', 'createdBy');
        return view('admin.price-calculator.show', [
            'estimation' => $priceEstimation,
        ]);
    }

    /**
     * Show form to edit a saved estimation.
     */
    public function edit(PriceEstimation $priceEstimation)
    {
        $priceEstimation->load('items');
        return view('admin.price-calculator.edit', [
            'estimation' => $priceEstimation,
        ]);
    }

    /**
     * Update an existing estimation.
     */
    public function update(StorePriceEstimationRequest $request, PriceEstimation $priceEstimation)
    {
        try {
            $data = $request->validated();
            if (empty($data['activity_participant_count']) && !empty($data['student_count'])) {
                $data['activity_participant_count'] = (int) $data['student_count'];
            }
            $result = $this->calculatorService->calculate($data);

            // Update existing estimation with new values
            $priceEstimation->update([
                'institution_name' => $data['institution_name'],
                'contact_person' => $data['contact_person'],
                'whatsapp' => $data['whatsapp'],
                'arrival_date' => $data['arrival_date'],
                'departure_date' => $data['departure_date'],
                'student_count' => (int) ($data['student_count'] ?? 0),
                'companion_count' => (int) ($data['companion_count'] ?? 0),
                'service_participant_count' => (int) ($data['service_participant_count'] ?? 0),
                'activity_participant_count' => (int) ($data['activity_participant_count'] ?? 0),
                'subtotal' => $result['subtotal'],
                'actual_price_per_person' => $result['actual_price_per_person'],
                'rounding_type' => $result['rounding_type'],
                'rounded_price_per_person' => $result['rounded_price_per_person'],
                'quotation_total' => $result['quotation_total'],
                'difference_amount' => $result['difference_amount'],
                'notes' => $data['notes'] ?? null,
            ]);

            // Delete old items and save new ones
            $priceEstimation->items()->delete();
            $sortOrder = 0;
            foreach ($result['items'] as $item) {
                \App\Models\PriceEstimationItem::create([
                    'price_estimation_id' => $priceEstimation->id,
                    'item_code' => $item['code'],
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'frequency' => $item['frequency'],
                    'unit' => $item['unit'],
                    'unit_price' => $item['unit_price'],
                    'price_per_person' => $item['price_per_person'],
                    'total' => $item['total'],
                    'calculation_details' => $item['calculation_details'],
                    'sort_order' => $sortOrder++,
                ]);
            }

            return redirect()
                ->route('admin.price-calculator.show', $priceEstimation)
                ->with('success', 'Estimasi berhasil diperbarui. Nomor: ' . $priceEstimation->estimation_number);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui estimasi: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate an existing estimation.
     */
    public function duplicate(PriceEstimation $priceEstimation)
    {
        $estimation = $priceEstimation->load('items');
        return view('admin.price-calculator.edit', [
            'estimation' => $estimation,
            'duplicate' => true,
        ]);
    }

    /**
     * Remove the specified estimation.
     */
    public function destroy(PriceEstimation $priceEstimation)
    {
        $priceEstimation->delete();

        return redirect()
            ->route('admin.price-calculator.index')
            ->with('success', 'Estimasi berhasil dihapus.');
    }

    /**
     * Recalculate an existing estimation with current prices.
     */
    public function recalculate(PriceEstimation $priceEstimation)
    {
        try {
            $estimation = $this->calculatorService->recalculate($priceEstimation);

            return redirect()
                ->route('admin.price-calculator.show', $estimation)
                ->with('success', 'Estimasi berhasil dihitung ulang dengan harga terbaru. Nomor estimasi baru: ' . $estimation->estimation_number);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghitung ulang estimasi: ' . $e->getMessage());
        }
    }

    /**
     * View PDF inline in browser.
     */
    public function pdfView(PriceEstimation $priceEstimation)
    {
        $priceEstimation->load('items', 'createdBy');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.price-calculator.pdf', [
            'estimation' => $priceEstimation,
        ]);

        return $pdf->stream('Estimasi-' . $priceEstimation->institution_name . '-' . $priceEstimation->estimation_number . '.pdf');
    }

    /**
     * Download PDF as attachment.
     */
    public function pdfDownload(PriceEstimation $priceEstimation)
    {
        $priceEstimation->load('items', 'createdBy');
        $safeName = preg_replace('/[^A-Za-z0-9\-_]/', '', str_replace(' ', '-', $priceEstimation->institution_name));
        $filename = 'Estimasi-' . $safeName . '-' . $priceEstimation->estimation_number . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.price-calculator.pdf', [
            'estimation' => $priceEstimation,
        ]);

        return $pdf->download($filename);
    }

    /**
     * Pricing settings index.
     */
    public function settings()
    {
        $components = \App\Models\PricingComponent::with('activeTiers')
            ->active()
            ->orderBy('sort_order')
            ->get();

        $addons = \App\Models\PricingAddon::active()
            ->orderBy('sort_order')
            ->get();

        return view('admin.price-calculator.settings', compact('components', 'addons'));
    }

    /**
     * Update component price.
     */
    public function updateComponentPrice(Request $request, \App\Models\PricingComponent $pricingComponent)
    {
        $validated = $request->validate([
            'default_price' => 'nullable|numeric|min:0',
        ]);

        $pricingComponent->update($validated);

        return redirect()
            ->route('admin.price-calculator.settings')
            ->with('success', 'Harga komponen berhasil diperbarui.');
    }

    /**
     * Update addon price.
     */
    public function updateAddonPrice(Request $request, \App\Models\PricingAddon $pricingAddon)
    {
        $validated = $request->validate([
            'price' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $pricingAddon->update($validated);

        return redirect()
            ->route('admin.price-calculator.settings')
            ->with('success', 'Harga add-on berhasil diperbarui.');
    }

    /**
     * Update pricing tier.
     */
    public function updateTier(Request $request, \App\Models\ParticipantPriceTier $participantPriceTier)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'additional_price_per_participant' => 'nullable|numeric|min:0',
        ]);

        $participantPriceTier->update($validated);

        return redirect()
            ->route('admin.price-calculator.settings')
            ->with('success', 'Tier harga berhasil diperbarui.');
    }
}