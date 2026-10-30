<?php

namespace App\Http\Controllers\Admin;

use App\Exports\HomestayExport;
use App\Http\Controllers\Controller;
use App\Imports\HomestayImport;
use App\Models\Homestay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class HomestayController extends Controller
{
    public function index()
    {
        $homestays = Homestay::ordered()->paginate(100);
        return view('admin.homestays.index', compact('homestays'));
    }

    public function create()
    {
        return view('admin.homestays.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_id' => 'required',
            'name_en' => 'nullable',
            'address_id' => 'nullable',
            'address_en' => 'nullable',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'capacity' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'nullable',
            'order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['order'] = $request->integer('order') ?? (Homestay::max('order') + 1);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('homestays', 'public');
        }

        Homestay::create($data);

        return redirect()->route('admin.homestays.index')->with([
            'message' => 'Homestay berhasil ditambahkan!',
            'alert-type' => 'success',
        ]);
    }

    public function edit(Homestay $homestay)
    {
        return view('admin.homestays.edit', compact('homestay'));
    }

    public function update(Request $request, Homestay $homestay)
    {
        $data = $request->validate([
            'name_id' => 'required',
            'name_en' => 'nullable',
            'address_id' => 'nullable',
            'address_en' => 'nullable',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'capacity' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'nullable',
            'order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['order'] = $request->integer('order') ?? $homestay->order;

        if ($request->hasFile('image')) {
            if ($homestay->image) {
                Storage::disk('public')->delete($homestay->image);
            }
            $data['image'] = $request->file('image')->store('homestays', 'public');
        }

        $homestay->update($data);

        return redirect()->route('admin.homestays.index')->with([
            'message' => 'Homestay berhasil diperbarui!',
            'alert-type' => 'info',
        ]);
    }

    public function destroy(Homestay $homestay)
    {
        if ($homestay->image) {
            Storage::disk('public')->delete($homestay->image);
        }
        $homestay->delete();

        return redirect()->route('admin.homestays.index')->with([
            'message' => 'Homestay berhasil dihapus!',
            'alert-type' => 'danger',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $path = $request->file('file')->getRealPath();

        \Excel::import(new HomestayImport, $path);

        return back()->with(['message' => 'Homestay berhasil diimport!', 'alert-type' => 'success']);
    }

    public function template()
    {
        return \Excel::download(new HomestayExport, 'homestay_template.xlsx');
    }
}
