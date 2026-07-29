<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProposalSetting;
use Illuminate\Http\Request;

class ProposalSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function index()
    {
        $settings = ProposalSetting::getSettings();
        return view('admin.proposal-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'short_profile' => 'nullable|string|max:5000',
            'vision' => 'nullable|string|max:2000',
            'mission' => 'nullable|string|max:2000',
            'advantages' => 'nullable|string|max:5000',
            'location' => 'nullable|string|max:2000',
            'maps_url' => 'nullable|url|max:500',
            'contact' => 'nullable|string|max:500',
            'tagline' => 'nullable|string|max:255',
            'commitment' => 'nullable|string|max:2000',
            'dp_terms' => 'nullable|string|max:2000',
            'cancellation_terms' => 'nullable|string|max:2000',
            'participant_change_terms' => 'nullable|string|max:2000',
            'force_majeure_terms' => 'nullable|string|max:2000',
            'payment_terms' => 'nullable|string|max:2000',
            'check_in_time' => 'nullable|string|max:10',
            'check_out_time' => 'nullable|string|max:10',
            'homestay_terms' => 'nullable|string|max:2000',
        ]);

        $settings = ProposalSetting::firstOrNew([]);
        $settings->fill($validated);
        $settings->save();

        return redirect()->back()->with('success', 'Pengaturan proposal berhasil diperbarui.');
    }
}