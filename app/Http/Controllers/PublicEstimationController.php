<?php

namespace App\Http\Controllers;

use App\Models\PriceEstimation;
use Illuminate\Http\Request;

class PublicEstimationController extends Controller
{
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