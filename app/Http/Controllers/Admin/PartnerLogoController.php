<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class PartnerLogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partnerLogos = PartnerLogo::orderBy('order')->paginate(10);

        return view('admin.partner_logos.index', compact('partnerLogos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.partner_logos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'url' => 'nullable|url|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'url', 'order', 'is_active']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('partner-logos', 'public');
        }

        if (!isset($data['order'])) {
            $data['order'] = PartnerLogo::max('order') + 1;
        }

        $data['is_active'] = $request->boolean('is_active');

        PartnerLogo::create($data);

        return redirect()->route('admin.partner_logos.index')
            ->with('message', 'Partner logo created successfully.')
            ->with('alert-type', 'success');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PartnerLogo $partnerLogo)
    {
        return view('admin.partner_logos.edit', compact('partnerLogo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PartnerLogo $partnerLogo)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'url' => 'nullable|url|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'url', 'order', 'is_active']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($partnerLogo->image) {
                Storage::disk('public')->delete($partnerLogo->image);
            }
            $data['image'] = $request->file('image')->store('partner-logos', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');

        $partnerLogo->update($data);

        return redirect()->route('admin.partner_logos.index')
            ->with('message', 'Partner logo updated successfully.')
            ->with('alert-type', 'success');
    }

    /**
     * Reorder partner logos via drag & drop.
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:partner_logos,id',
        ]);

        foreach ($request->ids as $order => $id) {
            PartnerLogo::where('id', $id)->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartnerLogo $partnerLogo)
    {
        if ($partnerLogo->image) {
            Storage::disk('public')->delete($partnerLogo->image);
        }

        $partnerLogo->delete();

        return redirect()->route('admin.partner_logos.index')
            ->with('message', 'Partner logo deleted successfully.')
            ->with('alert-type', 'success');
    }
}