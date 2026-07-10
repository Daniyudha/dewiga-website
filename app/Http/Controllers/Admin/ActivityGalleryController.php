<?php

namespace App\Http\Controllers\Admin;

use App\Models\Activity;
use App\Models\ActivityGallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class ActivityGalleryController extends Controller
{
    public function store(Request $request, Activity $activity)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:png,jpg,jpeg'],
            'name_id' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $path = $request->file('image')->store('activity_galleries', 'public');

        ActivityGallery::create([
            'activity_id' => $activity->id,
            'image' => $path,
            'name_id' => $request->name_id,
            'name_en' => $request->name_en,
            'order' => $activity->galleries()->count() + 1,
        ]);

        return redirect()->route('admin.activities.edit', [$activity])->with([
            'message' => 'Gallery image added successfully!',
            'alert-type' => 'success'
        ]);
    }

    public function edit(Activity $activity, ActivityGallery $gallery)
    {
        return view('admin.activity-galleries.edit', compact('activity', 'gallery'));
    }

    public function update(Request $request, Activity $activity, ActivityGallery $gallery)
    {
        $request->validate([
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg'],
            'name_id' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['name_id', 'name_en']);

        if ($request->hasFile('image')) {
            File::delete('storage/' . $gallery->image);
            $data['image'] = $request->file('image')->store('activity_galleries', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.activities.edit', [$activity])->with([
            'message' => 'Gallery image updated successfully!',
            'alert-type' => 'info'
        ]);
    }

    public function destroy(Activity $activity, ActivityGallery $gallery)
    {
        File::delete('storage/' . $gallery->image);
        $gallery->delete();

        return redirect()->back()->with([
            'message' => 'Gallery image deleted!',
            'alert-type' => 'danger'
        ]);
    }
}