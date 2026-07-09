<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::orderBy('order')->get();
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
            'order' => 'nullable|integer',
            'is_featured' => 'nullable',
        ]);

        $data['slug'] = Str::slug($data['title_id']);
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('activities', 'public');
        }

        Activity::create($data);

        return redirect()->route('admin.activities.index')->with([
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
            'order' => 'nullable|integer',
            'is_featured' => 'nullable',
        ]);

        $data['slug'] = Str::slug($data['title_id']);
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            // Delete old image
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
        // Delete image
        if ($activity->image) {
            Storage::disk('public')->delete($activity->image);
        }

        $activity->delete();

        return redirect()->route('admin.activities.index')->with([
            'message' => 'Aktivitas berhasil dihapus!',
            'alert-type' => 'danger'
        ]);
    }
}
