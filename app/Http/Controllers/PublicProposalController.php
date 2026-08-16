<?php

namespace App\Http\Controllers;

use App\Models\PriceEstimation;
use Illuminate\Http\Request;

class PublicProposalController extends Controller
{
    /**
     * Download proposal PDF (same as admin PDF, no login required).
     */
    public function pdf(string $estimationNumber)
    {
        $proposal = PriceEstimation::where('estimation_number', $estimationNumber)
            ->with(['items' => fn($q) => $q->orderBy('sort_order'), 'createdBy'])
            ->firstOrFail();

        $settings = \App\Models\ProposalSetting::first();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.proposals.pdf', [
            'proposal' => $proposal,
            'settings' => $settings,
        ]);

        $safeName = preg_replace('/[^A-Za-z0-9\-_]/', '', str_replace(' ', '-', $proposal->institution_name));
        $filename = 'Proposal-' . $safeName . '-' . ($proposal->proposal_number ?? $proposal->estimation_number) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Show a proposal publicly without login (for WhatsApp sharing).
     */
    public function show(string $estimationNumber)
    {
        $estimation = PriceEstimation::where('estimation_number', $estimationNumber)
            ->with(['items' => fn($q) => $q->orderBy('sort_order'), 'createdBy'])
            ->firstOrFail();

        $settings = \App\Models\ProposalSetting::first();
        $hasRundown = false;
        /** @var \Illuminate\Support\Collection $rundownItems */
        $rundownItems = collect();
        if ($estimation->rundown_template_id) {
            /** @var \App\Models\RundownTemplate|null $template */
            $template = \App\Models\RundownTemplate::with('items')->find($estimation->rundown_template_id);
            if ($template !== null) {
                $hasRundown = true;
                /** @var \Illuminate\Support\Collection $groupedItems */
                $groupedItems = $template->items->groupBy('day_number');
                $rundownItems = $groupedItems;
            }
        }
        $allActivities = \App\Models\Activity::orderBy('order')->get();
        $defaultFacilities = ['Homestay', 'Makan', 'Snack', 'Pemandu', 'Pilih 5 Aktivitas', 'Transportasi', 'Dokumentasi', 'Dukungan Audio Dasar', 'Welcome Drink', 'Parkir', 'Pendopo'];

        return view('public.proposals.show', compact('estimation', 'settings', 'hasRundown', 'rundownItems', 'allActivities', 'defaultFacilities'));
    }
}