<?php

namespace App\Http\Controllers;

use App\Models\PriceEstimation;
use Illuminate\Http\Request;

class PublicEstimationController extends Controller
{
    /**
     * Download estimation PDF (same as admin PDF, no login required).
     */
    public function pdf(string $estimationNumber)
    {
        $estimation = PriceEstimation::where('estimation_number', $estimationNumber)
            ->with(['items' => function ($q) {
                $q->orderBy('sort_order');
            }, 'createdBy'])
            ->firstOrFail();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.price-calculator.pdf', [
            'estimation' => $estimation,
        ]);

        $safeName = preg_replace('/[^A-Za-z0-9\-_]/', '', str_replace(' ', '-', $estimation->institution_name));
        $filename = 'Estimasi-' . $safeName . '-' . $estimation->estimation_number . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Show an estimation publicly without login (for WhatsApp sharing).
     */
    public function show(string $estimationNumber)
    {
        $estimation = PriceEstimation::where('estimation_number', $estimationNumber)
            ->with(['items' => function ($q) {
                $q->orderBy('sort_order');
            }, 'createdBy'])
            ->firstOrFail();

        return view('public.estimations.show', compact('estimation'));
    }
}