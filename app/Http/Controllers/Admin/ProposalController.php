<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProposalStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePriceEstimationRequest;
use App\Models\PriceEstimation;
use App\Models\PriceEstimationItem;
use App\Models\RundownTemplate;
use App\Models\ProposalSetting;
use App\Services\PriceCalculatorService;
use App\Services\PriceEstimationConversionService;
use App\Services\RundownService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    protected PriceCalculatorService $calculatorService;
    protected PriceEstimationConversionService $conversionService;
    protected RundownService $rundownService;

    public function __construct(
        PriceCalculatorService $calculatorService,
        PriceEstimationConversionService $conversionService,
        RundownService $rundownService
    ) {
        $this->middleware('is_admin');
        $this->calculatorService = $calculatorService;
        $this->conversionService = $conversionService;
        $this->rundownService = $rundownService;
    }

    /**
     * Generate unique proposal number (PRP-2026-0001).
     */
    public static function generateProposalNumber(): string
    {
        $year = now()->year;
        $last = PriceEstimation::whereYear('created_at', $year)
            ->whereNotNull('proposal_number')
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $last ? ((int) substr($last->proposal_number, -4)) + 1 : 1;
        return 'PRP-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function index(Request $request)
    {
        $query = PriceEstimation::with('createdBy');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('proposal_number', 'like', "%{$search}%")
                  ->orWhere('estimation_number', 'like', "%{$search}%")
                  ->orWhere('institution_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('proposal_status', $status);
        }

        $proposals = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.proposals.index', compact('proposals'));
    }

    public function create()
    {
        $templates = RundownTemplate::where('is_active', true)->orderBy('name')->get();
        return view('admin.proposals.create', compact('templates'));
    }

    public function calculate(StorePriceEstimationRequest $request)
    {
        $data = $request->validated();
        if (empty($data['activity_participant_count']) && !empty($data['student_count'])) {
            $data['activity_participant_count'] = (int) $data['student_count'];
        }
        $result = $this->calculatorService->calculate($data);
        return response()->json($result);
    }

    public function store(StorePriceEstimationRequest $request)
    {
        try {
            $data = $request->validated();
            if (empty($data['activity_participant_count']) && !empty($data['student_count'])) {
                $data['activity_participant_count'] = (int) $data['student_count'];
            }

            $estimation = DB::transaction(function () use ($data) {
                // First save estimation with calculator
                $estimation = $this->calculatorService->save($data);

                // Generate proposal number and set proposal data
                $estimation->update([
                    'proposal_number' => self::generateProposalNumber(),
                    'proposal_status' => ProposalStatus::DRAFT,
                    'proposal_title' => $data['proposal_title'] ?? 'Program ' . $estimation->institution_name,
                    'program_objective' => $data['program_objective'] ?? null,
                    'rundown_template_id' => $data['rundown_template_id'] ?? null,
                    'proposal_version' => 1,
                ]);

                return $estimation;
            });

            return redirect()
                ->route('admin.proposals.show', $estimation)
                ->with('success', 'Proposal berhasil dibuat. Nomor: ' . $estimation->proposal_number);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat proposal: ' . $e->getMessage());
        }
    }

    public function show(PriceEstimation $priceEstimation)
    {
        $proposal = $priceEstimation;
        $proposal->load('items', 'createdBy');
        $templates = RundownTemplate::where('is_active', true)->orderBy('name')->get();
        $settings = ProposalSetting::getSettings();

        $isConverted = $proposal->proposal_status === ProposalStatus::CONVERTED && $proposal->converted_schedule_id;
        $convertedSchedule = $isConverted ? \App\Models\Schedule::find($proposal->converted_schedule_id) : null;

        return view('admin.proposals.show', compact('proposal', 'templates', 'settings', 'isConverted', 'convertedSchedule'));
    }

    public function edit(PriceEstimation $priceEstimation)
    {
        $priceEstimation->load('items');
        $templates = RundownTemplate::where('is_active', true)->orderBy('name')->get();
        return view('admin.proposals.edit', [
            'proposal' => $priceEstimation,
            'templates' => $templates,
        ]);
    }

    public function update(StorePriceEstimationRequest $request, PriceEstimation $priceEstimation)
    {
        if ($priceEstimation->proposal_status === ProposalStatus::CONVERTED) {
            return redirect()->back()->with('error', 'Proposal yang sudah dikonversi tidak dapat diedit.');
        }

        try {
            $data = $request->validated();
            if (empty($data['activity_participant_count']) && !empty($data['student_count'])) {
                $data['activity_participant_count'] = (int) $data['student_count'];
            }

            $result = $this->calculatorService->calculate($data);

            DB::transaction(function () use ($priceEstimation, $data, $result) {
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
                    'proposal_title' => $data['proposal_title'] ?? $priceEstimation->proposal_title,
                    'program_objective' => $data['program_objective'] ?? $priceEstimation->program_objective,
                    'rundown_template_id' => $data['rundown_template_id'] ?? $priceEstimation->rundown_template_id,
                ]);

                $priceEstimation->items()->delete();
                $sortOrder = 0;
                foreach ($result['items'] as $item) {
                    PriceEstimationItem::create([
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
            });

            return redirect()
                ->route('admin.proposals.show', $priceEstimation)
                ->with('success', 'Proposal berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function destroy(PriceEstimation $priceEstimation)
    {
        if ($priceEstimation->proposal_status === ProposalStatus::CONVERTED) {
            return redirect()->back()->with('error', 'Proposal yang sudah dikonversi tidak dapat dihapus.');
        }
        $priceEstimation->delete();
        return redirect()->route('admin.proposals.index')
            ->with('success', 'Proposal berhasil dihapus.');
    }

    public function duplicate(PriceEstimation $priceEstimation)
    {
        $estimation = $priceEstimation->load('items');
        return view('admin.proposals.edit', [
            'proposal' => $estimation,
            'duplicate' => true,
            'templates' => RundownTemplate::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Update proposal program details (title, subtitle, objectives, etc.)
     */
    public function updateProgram(Request $request, PriceEstimation $priceEstimation)
    {
        $validated = $request->validate([
            'proposal_title' => 'nullable|string|max:255',
            'program_subtitle' => 'nullable|string|max:255',
            'program_objective' => 'nullable|string|max:1000',
            'learning_outputs' => 'nullable|string|max:2000',
            'target_participants' => 'nullable|string|max:500',
            'village_advantages' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:2000',
            'rundown_template_id' => 'nullable|exists:rundown_templates,id',
        ]);

        $priceEstimation->update($validated);

        return redirect()->back()->with('success', 'Data program berhasil diperbarui.');
    }

    /**
     * Update facilities checklist.
     */
    public function updateFacilities(Request $request, PriceEstimation $priceEstimation)
    {
        $validated = $request->validate([
            'facilities' => 'nullable|array',
            'facilities.*' => 'string',
        ]);

        $priceEstimation->update([
            'facilities' => $validated['facilities'] ?? [],
        ]);

        return redirect()->back()->with('success', 'Fasilitas berhasil diperbarui.');
    }

    /**
     * Update proposal status.
     */
    public function updateStatus(Request $request, PriceEstimation $priceEstimation)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', ProposalStatus::all()),
        ]);

        $newStatus = $validated['status'];
        $oldStatus = $priceEstimation->proposal_status;

        $transitions = ProposalStatus::validTransitions();
        if (!isset($transitions[$oldStatus]) || !in_array($newStatus, $transitions[$oldStatus])) {
            return redirect()->back()->with('error', 'Transisi status tidak valid.');
        }

        $updateData = ['proposal_status' => $newStatus];

        if ($newStatus === ProposalStatus::SENT) {
            $updateData['proposal_sent_at'] = now();
        }
        if ($newStatus === ProposalStatus::APPROVED) {
            $updateData['approved_at'] = now();
        }

        $priceEstimation->update($updateData);

        $message = 'Status proposal berhasil diubah menjadi ' . ProposalStatus::label($newStatus);
        return redirect()->back()->with('success', $message);
    }

    /**
     * Convert proposal to schedule (brings rundown data too).
     */
    public function convertToSchedule(Request $request, PriceEstimation $priceEstimation)
    {
        if ($priceEstimation->proposal_status !== ProposalStatus::APPROVED) {
            return redirect()->back()->with('error', 'Hanya proposal dengan status "Disetujui" yang dapat dikonversi.');
        }

        try {
            $schedule = $this->conversionService->convert($priceEstimation, $request->all());

            // Relate schedule back to proposal
            $priceEstimation->update([
                'proposal_status' => ProposalStatus::CONVERTED,
                'converted_schedule_id' => $schedule->id,
            ]);

            // Copy rundown if template was selected
            if ($priceEstimation->rundown_template_id) {
                $this->rundownService->copyTemplateToSchedule($schedule, $priceEstimation->rundown_template_id);
            }

            return redirect()
                ->route('admin.schedules.show', $schedule)
                ->with('success', 'Proposal ' . $priceEstimation->proposal_number . ' berhasil dikonversi menjadi jadwal!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengkonversi: ' . $e->getMessage());
        }
    }

    /**
     * View PDF proposal with full layout and bg-doc.png background.
     */
    public function pdfView(PriceEstimation $priceEstimation)
    {
        $proposal = $priceEstimation->load('items');
        $settings = ProposalSetting::getSettings();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.proposals.pdf', [
            'proposal' => $proposal,
            'settings' => $settings,
        ]);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Proposal-' . $proposal->institution_name . '-' . ($proposal->proposal_number ?? $proposal->estimation_number) . '.pdf');
    }

    /**
     * Download PDF proposal.
     */
    public function pdfDownload(PriceEstimation $priceEstimation)
    {
        $proposal = $priceEstimation->load('items');
        $settings = ProposalSetting::getSettings();
        $safeName = preg_replace('/[^A-Za-z0-9\-_]/', '', str_replace(' ', '-', $proposal->institution_name));

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.proposals.pdf', [
            'proposal' => $proposal,
            'settings' => $settings,
        ]);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Proposal-' . $safeName . '-' . ($proposal->proposal_number ?? $proposal->estimation_number) . '.pdf');
    }

    /**
     * Send proposal via WhatsApp.
     */
    public function sendWhatsApp(PriceEstimation $priceEstimation)
    {
        $proposal = $priceEstimation;
        $waNum = preg_replace('/[^0-9]/', '', $proposal->whatsapp);
        if (substr($waNum, 0, 1) === '0') $waNum = '62' . substr($waNum, 1);

        $proposal->update(['proposal_status' => ProposalStatus::SENT, 'proposal_sent_at' => now()]);

        return redirect()->away("https://wa.me/{$waNum}?text=" . rawurlencode(
            "Halo Bapak/Ibu.\n\n" .
            "Berikut kami kirimkan Proposal Program Desa Wisata Gabugan.\n\n" .
            "Silakan dipelajari.\n\n" .
            "Apabila terdapat revisi kegiatan maupun jumlah peserta, kami dengan senang hati akan menyesuaikan.\n\n" .
            "Terima kasih.\n\n" .
            "Link PDF: " . route('admin.proposals.pdf-view', $proposal)
        ));
    }

    /**
     * Convert an existing PriceEstimation to a Proposal Program.
     * Copies all data including items and creates a new proposal number.
     */
    public function convertEstimationToProposal(PriceEstimation $priceEstimation)
    {
        if ($priceEstimation->proposal_number) {
            return redirect()->route('admin.proposals.show', $priceEstimation)
                ->with('info', 'Estimasi ini sudah memiliki proposal: ' . $priceEstimation->proposal_number);
        }

        $priceEstimation->update([
            'proposal_number' => self::generateProposalNumber(),
            'proposal_status' => ProposalStatus::DRAFT,
            'proposal_title' => 'Proposal Program ' . $priceEstimation->institution_name,
            'proposal_version' => 1,
        ]);

        return redirect()->route('admin.proposals.show', $priceEstimation)
            ->with('success', 'Proposal berhasil dibuat dari estimasi ' . $priceEstimation->estimation_number . '! Nomor: ' . $priceEstimation->proposal_number);
    }

    /**
     * Recalculate with current prices.
     */
    public function recalculate(PriceEstimation $priceEstimation)
    {
        try {
            $estimation = $this->calculatorService->recalculate($priceEstimation);
            return redirect()
                ->route('admin.proposals.show', $estimation)
                ->with('success', 'Proposal berhasil dihitung ulang dengan harga terbaru.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghitung ulang: ' . $e->getMessage());
        }
    }

    /**
     * Settings page (redirect to price-calculator settings for now).
     */
    public function settings()
    {
        return app(PriceCalculatorController::class)->settings();
    }
}