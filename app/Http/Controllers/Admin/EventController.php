<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Event;
use App\Models\Homestay;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderByDesc('start_date')->paginate(100);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_id' => 'required',
            'name_en' => 'nullable',
            'description_id' => 'nullable',
            'description_en' => 'nullable',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'participant_count' => 'nullable|integer|min:0',
            'is_active' => 'nullable',
        ]);

        $data['slug'] = Str::slug($data['name_id']);
        $data['is_active'] = $request->has('is_active');
        $data['participant_count'] = (int) $request->integer('participant_count') ?? 0;

        Event::create($data);

        return redirect()->route('admin.schedules.index', ['tab' => 'peta-event'])->with([
            'message' => 'Event berhasil ditambahkan!',
            'alert-type' => 'success',
        ]);
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'name_id' => 'required',
            'name_en' => 'nullable',
            'description_id' => 'nullable',
            'description_en' => 'nullable',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'participant_count' => 'nullable|integer|min:0',
            'is_active' => 'nullable',
        ]);

        $data['slug'] = Str::slug($data['name_id']);
        $data['is_active'] = $request->has('is_active');
        $data['participant_count'] = (int) $request->integer('participant_count') ?? 0;

        $event->update($data);

        return redirect()->route('admin.schedules.index', ['tab' => 'peta-event'])->with([
            'message' => 'Event berhasil diperbarui!',
            'alert-type' => 'info',
        ]);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.schedules.index', ['tab' => 'peta-event'])->with([
            'message' => 'Event berhasil dihapus!',
            'alert-type' => 'danger',
        ]);
    }

    /**
     * Form untuk menentukan homestay mana yang digunakan event ini dan
     * oleh kelompok mana homestay tersebut ditempati.
     */
    public function assignHomestays(Event $event)
    {
        $homestays = Homestay::active()->ordered()->get();
        $assigned = $event->homestays()->active()->ordered()->get()->pluck('pivot.group_name_id', 'id');
        return view('admin.events.assign-homestays', compact('event', 'homestays', 'assigned'));
    }

    public function storeAssignHomestays(Request $request, Event $event)
    {
        $request->validate([
            'homestays' => 'nullable|array',
            'homestays.*' => 'integer|exists:homestays,id',
            'group_name_id' => 'nullable|array',
            'group_name_id.*' => 'nullable|string',
            'group_name_en' => 'nullable|array',
            'group_name_en.*' => 'nullable|string',
        ]);

        $event->homestays()->detach();

        foreach ($request->input('homestays', []) as $homestayId) {
            $event->homestays()->attach($homestayId, [
                'group_name_id' => $request->input("group_name_id.{$homestayId}"),
                'group_name_en' => $request->input("group_name_en.{$homestayId}"),
                'notes' => null,
            ]);
        }

        return redirect()->route('admin.schedules.index', ['tab' => 'peta-event'])->with([
            'message' => 'Pengaturan homestay event berhasil disimpan!',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Form untuk menentukan aktivitas mana yang muncul untuk event ini.
     */
    public function assignActivities(Event $event)
    {
        $activities = Activity::active()->ordered()->get();
        $assigned = $event->activities()->pluck('activity_id');
        return view('admin.events.assign-activities', compact('event', 'activities', 'assigned'));
    }

    public function storeAssignActivities(Request $request, Event $event)
    {
        $request->validate([
            'activities' => 'nullable|array',
            'activities.*' => 'integer|exists:activities,id',
        ]);

        $event->activities()->sync($request->input('activities', []));

        return redirect()->route('admin.schedules.index', ['tab' => 'peta-event'])->with([
            'message' => 'Pengaturan aktivitas event berhasil disimpan!',
            'alert-type' => 'success',
        ]);
    }
}
