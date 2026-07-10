<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::orderBy('order')->paginate(100);
        return view('admin.activities.index', compact('activities'));
    }

    public function create()
    {
        return view('admin.activities.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title_id' => 'required',
            'title_en' => 'nullable',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'duration_id' => 'nullable',
            'duration_en' => 'nullable',
            'min_pax' => 'nullable',
            'includes_id' => 'nullable',
            'includes_en' => 'nullable',
            'description_id' => 'required',
            'description_en' => 'nullable',
            'is_featured' => 'nullable',
        ]);

        $data['slug'] = Str::slug($data['title_id']);
        $data['is_featured'] = $request->has('is_featured');

        // Auto-order: set to max order + 1
        $data['order'] = Activity::max('order') + 1;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('activities', 'public');
        }

        $activity = Activity::create($data);

        // Handle optional gallery image upload
        if ($request->hasFile('gallery_image')) {
            $path = $request->file('gallery_image')->store('activity_galleries', 'public');
            ActivityGallery::create([
                'activity_id' => $activity->id,
                'image' => $path,
                'name_id' => $request->gallery_name_id,
                'name_en' => $request->gallery_name_en,
                'order' => 1,
            ]);
        }

        return redirect()->route('admin.activities.edit', [$activity])->with([
            'message' => 'Aktivitas berhasil dibuat!',
            'alert-type' => 'success'
        ]);
    }

    public function show(Activity $activity)
    {
        return redirect()->route('activities.show', $activity->slug);
    }

    public function edit(Activity $activity)
    {
        return view('admin.activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'title_id' => 'required',
            'title_en' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'duration_id' => 'nullable',
            'duration_en' => 'nullable',
            'min_pax' => 'nullable',
            'includes_id' => 'nullable',
            'includes_en' => 'nullable',
            'description_id' => 'required',
            'description_en' => 'nullable',
            'is_featured' => 'nullable',
        ]);

        $data['slug'] = Str::slug($data['title_id']);
        $data['is_featured'] = $request->has('is_featured');

        // Validate featured limit (max 6)
        if ($data['is_featured'] && !$activity->is_featured) {
            $featuredCount = Activity::where('is_featured', true)->count();
            if ($featuredCount >= 6) {
                return redirect()->back()->withInput()->with([
                    'message' => 'Maksimal 6 aktivitas yang dapat difeatured!',
                    'alert-type' => 'danger'
                ]);
            }
        }

        if ($request->hasFile('image')) {
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            $data['image'] = $request->file('image')->store('activities', 'public');
        }

        $activity->update($data);

        return redirect()->route('admin.activities.index')->with([
            'message' => 'Aktivitas berhasil diperbarui!',
            'alert-type' => 'info'
        ]);
    }

    public function destroy(Activity $activity)
    {
        if ($activity->image) {
            Storage::disk('public')->delete($activity->image);
        }

        $activity->delete();

        return redirect()->route('admin.activities.index')->with([
            'message' => 'Aktivitas berhasil dihapus!',
            'alert-type' => 'danger'
        ]);
    }

    /**
     * Reorder activities via drag & drop.
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:activities,id',
        ]);

        foreach ($request->ids as $order => $id) {
            Activity::where('id', $id)->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }
}