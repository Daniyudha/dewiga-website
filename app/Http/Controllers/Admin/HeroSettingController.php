<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSetting;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSettingController extends Controller
{
    public function index()
    {
        $heroes = HeroSetting::with('slides')->orderBy('page')->get();
        return view('admin.hero-settings.index', compact('heroes'));
    }

    public function edit(HeroSetting $heroSetting)
    {
        $heroSetting->load('slides');
        return view('admin.hero-settings.edit', compact('heroSetting'));
    }

    public function update(Request $request, HeroSetting $heroSetting)
    {
        $data = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($heroSetting->image) {
                Storage::delete($heroSetting->image);
            }
            $data['image'] = $request->file('image')->store('hero-images', 'public');
        }

        $heroSetting->update($data);

        return redirect()->route('admin.hero-settings.index')
            ->with('success', 'Hero setting updated successfully.');
    }

    public function uploadSlide(Request $request, HeroSetting $heroSetting)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'alt_text' => 'nullable|string|max:255',
        ]);

        // Check max 5 slides for home page
        if ($heroSetting->page === 'home' && $heroSetting->slides()->count() >= 5) {
            return back()->with('error', 'Maximum 5 slides allowed for homepage hero.');
        }

        // Check max 1 slide for other pages
        if ($heroSetting->page !== 'home' && $heroSetting->slides()->count() >= 1) {
            return back()->with('error', 'Only 1 image allowed for this page hero.');
        }

        $path = $request->file('image')->store('hero-slides', 'public');

        $maxOrder = $heroSetting->slides()->max('order') ?? 0;

        $heroSetting->slides()->create([
            'image' => $path,
            'alt_text' => $request->alt_text ?? $heroSetting->page . ' hero',
            'order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Slide image uploaded successfully.');
    }

    public function deleteSlide(HeroSetting $heroSetting, $heroSlide)
    {
        $slide = HeroSlide::where('id', $heroSlide)
            ->where('hero_setting_id', $heroSetting->id)
            ->firstOrFail();

        if ($slide->image) {
            Storage::delete($slide->image);
        }
        $slide->delete();

        return back()->with('success', 'Slide image deleted successfully.');
    }

    public function reorderSlides(Request $request, HeroSetting $heroSetting)
    {
        $request->validate([
            'slides' => 'required|array',
            'slides.*.id' => 'required|exists:hero_slides,id',
            'slides.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->slides as $slideData) {
            HeroSlide::where('id', $slideData['id'])
                ->where('hero_setting_id', $heroSetting->id)
                ->update(['order' => $slideData['order']]);
        }

        return response()->json(['success' => true]);
    }
}
