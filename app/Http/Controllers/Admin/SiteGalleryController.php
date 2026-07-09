<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteGalleryController extends Controller
{
    public function index()
    {
        $galleries = SiteGallery::orderBy('order')->get();
        return view('admin.site-galleries.index', compact('galleries'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $uploaded = [];
        foreach ($request->file('images') as $file) {
            $path = $file->store('site-galleries', 'public');
            $gallery = SiteGallery::create([
                'image' => $path,
                'title' => '',
                'order' => SiteGallery::max('order') + 1,
            ]);
            $uploaded[] = $gallery;
        }

        return response()->json([
            'success' => true,
            'message' => count($uploaded) . ' gambar berhasil diupload',
            'data' => $uploaded,
        ]);
    }

    public function updateTitle(Request $request, SiteGallery $siteGallery)
    {
        $request->validate(['title' => 'nullable|string|max:255']);
        $siteGallery->update(['title' => $request->title]);
        return response()->json(['success' => true, 'message' => 'Title updated']);
    }

    public function destroy(SiteGallery $siteGallery)
    {
        Storage::disk('public')->delete($siteGallery->image);
        $siteGallery->delete();
        return redirect()->back()->with([
            'message' => 'Gambar berhasil dihapus!',
            'alert-type' => 'danger'
        ]);
    }

    public function reorder(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        foreach ($request->ids as $index => $id) {
            SiteGallery::where('id', $id)->update(['order' => $index]);
        }
        return response()->json(['success' => true]);
    }
}