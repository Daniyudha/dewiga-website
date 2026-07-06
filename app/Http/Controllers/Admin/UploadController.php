<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * Handle CKEditor image upload.
     * Stores the uploaded image and returns the URL.
     */
    public function image(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $file = $request->file('upload');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('ckeditor/images', $filename, 'public');

        $url = asset('storage/' . $path);

        return response()->json([
            'url' => $url,
        ]);
    }
}
