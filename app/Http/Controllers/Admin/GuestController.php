<?php

namespace App\Http\Controllers\Admin;

use App\Models\Guest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $query = Guest::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('institution', 'like', "%{$search}%")
                  ->orWhere('number_phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $guests = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.guests.index', compact('guests'));
    }

    public function create()
    {
        return view('admin.guests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'nullable|string|max:255',
            'number_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['source'] = 'manual';

        Guest::create($validated);

        return redirect()->route('admin.guests.index')->with([
            'message' => 'Tamu berhasil ditambahkan!',
            'alert-type' => 'success',
        ]);
    }

    public function edit(Guest $guest)
    {
        return view('admin.guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'nullable|string|max:255',
            'number_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $guest->update($validated);

        return redirect()->route('admin.guests.index')->with([
            'message' => 'Data tamu berhasil diperbarui!',
            'alert-type' => 'success',
        ]);
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();

        return redirect()->route('admin.guests.index')->with([
            'message' => 'Tamu berhasil dihapus!',
            'alert-type' => 'success',
        ]);
    }
}