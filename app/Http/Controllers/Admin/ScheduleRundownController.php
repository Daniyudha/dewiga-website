<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScheduleRundownRequest;
use App\Models\Schedule;
use App\Models\ScheduleRundown;
use App\Models\ScheduleRundownItem;
use App\Models\RundownTemplate;
use App\Services\RundownService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleRundownController extends Controller
{
    protected $rundownService;

    public function __construct(RundownService $rundownService)
    {
        $this->middleware('is_admin');
        $this->rundownService = $rundownService;
    }

    /**
     * Show the rundown tab on schedule detail page.
     */
    public function show(Schedule $schedule)
    {
        $schedule->load('scheduleRundown.items');
        $templates = RundownTemplate::where('is_active', true)->orderBy('name')->get();

        return view('admin.schedules.tabs.rundown', compact('schedule', 'templates'));
    }

    /**
     * Create rundown from template.
     */
    public function createFromTemplate(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'rundown_template_id' => 'required|exists:rundown_templates,id',
            'title' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Check if rundown already exists
        if ($schedule->scheduleRundown) {
            return redirect()->back()->with([
                'message' => 'Jadwal ini sudah memiliki rundown. Hapus rundown yang ada terlebih dahulu untuk membuat yang baru.',
                'alert-type' => 'warning',
            ]);
        }

        $template = RundownTemplate::with('items')->findOrFail($validated['rundown_template_id']);

        if (!$template->is_active) {
            return redirect()->back()->with([
                'message' => 'Template tidak aktif. Pilih template yang aktif.',
                'alert-type' => 'error',
            ]);
        }

        $rundown = DB::transaction(function () use ($schedule, $template, $validated) {
            $title = $validated['title'] ?? $template->name . ' - ' . ($schedule->visitor_name ?? 'Jadwal #' . $schedule->id);

            $rundown = ScheduleRundown::create([
                'schedule_id' => $schedule->id,
                'rundown_template_id' => $template->id,
                'rundown_number' => ScheduleRundown::generateRundownNumber(),
                'title' => $title,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($template->items as $templateItem) {
                $activityDate = $schedule->start_date ? $schedule->start_date->copy()->addDays($templateItem->day_number - 1) : null;

                ScheduleRundownItem::create([
                    'schedule_rundown_id' => $rundown->id,
                    'schedule_id' => $schedule->id,
                    'day_number' => $templateItem->day_number,
                    'activity_date' => $activityDate,
                    'start_time' => $templateItem->start_time,
                    'end_time' => $templateItem->end_time,
                    'activity_id' => $templateItem->activity_id,
                    'activity_name' => $templateItem->activity_name,
                    'location' => $templateItem->location,
                    'person_in_charge' => $templateItem->person_in_charge,
                    'description' => $templateItem->description,
                    'sort_order' => $templateItem->sort_order,
                ]);
            }

            return $rundown;
        });

        return redirect()->route('admin.schedules.show', $schedule)
            ->with(['message' => 'Rundown berhasil dibuat dari template!', 'alert-type' => 'success']);
    }

    /**
     * Create empty rundown.
     */
    public function createEmpty(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ]);

        if ($schedule->scheduleRundown) {
            return redirect()->back()->with([
                'message' => 'Jadwal ini sudah memiliki rundown.',
                'alert-type' => 'warning',
            ]);
        }

        $rundown = ScheduleRundown::create([
            'schedule_id' => $schedule->id,
            'rundown_number' => ScheduleRundown::generateRundownNumber(),
            'title' => $validated['title'] ?? 'Rundown ' . ($schedule->visitor_name ?? 'Jadwal #' . $schedule->id),
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.schedules.show', $schedule)
            ->with(['message' => 'Rundown kosong berhasil dibuat!', 'alert-type' => 'success']);
    }

    /**
     * Edit rundown - show edit page.
     */
    public function edit(Schedule $schedule, ScheduleRundown $rundown)
    {
        $rundown->load('items');
        return view('admin.schedules.rundown-edit', compact('schedule', 'rundown'));
    }

    /**
     * Update rundown metadata.
     */
    public function update(ScheduleRundownRequest $request, Schedule $schedule, ScheduleRundown $rundown)
    {
        $rundown->update([
            'title' => $request->title,
            'notes' => $request->notes,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.schedules.show', $schedule)
            ->with(['message' => 'Rundown berhasil diperbarui!', 'alert-type' => 'success']);
    }

    /**
     * Delete rundown.
     */
    public function destroy(Schedule $schedule, ScheduleRundown $rundown)
    {
        $rundown->delete();

        return redirect()->route('admin.schedules.show', $schedule)
            ->with(['message' => 'Rundown berhasil dihapus!', 'alert-type' => 'success']);
    }

    /**
     * Reset rundown from template (replace items).
     */
    public function resetFromTemplate(Request $request, Schedule $schedule, ScheduleRundown $rundown)
    {
        $validated = $request->validate([
            'rundown_template_id' => 'required|exists:rundown_templates,id',
        ]);

        $template = RundownTemplate::with('items')->findOrFail($validated['rundown_template_id']);

        DB::transaction(function () use ($schedule, $rundown, $template) {
            $rundown->items()->delete();
            $rundown->update(['rundown_template_id' => $template->id]);

            foreach ($template->items as $templateItem) {
                $activityDate = $schedule->start_date ? $schedule->start_date->copy()->addDays($templateItem->day_number - 1) : null;

                ScheduleRundownItem::create([
                    'schedule_rundown_id' => $rundown->id,
                    'schedule_id' => $schedule->id,
                    'day_number' => $templateItem->day_number,
                    'activity_date' => $activityDate,
                    'start_time' => $templateItem->start_time,
                    'end_time' => $templateItem->end_time,
                    'activity_id' => $templateItem->activity_id,
                    'activity_name' => $templateItem->activity_name,
                    'location' => $templateItem->location,
                    'person_in_charge' => $templateItem->person_in_charge,
                    'description' => $templateItem->description,
                    'sort_order' => $templateItem->sort_order,
                ]);
            }
        });

        return redirect()->route('admin.schedules.show', $schedule)
            ->with(['message' => 'Rundown berhasil di-reset dari template!', 'alert-type' => 'success']);
    }

    /**
     * Add item to rundown.
     */
    public function addItem(Request $request, Schedule $schedule, ScheduleRundown $rundown)
    {
        $validated = $request->validate([
            'day_number' => 'required|integer|min:1',
            'activity_date' => 'nullable|date',
            'start_time' => 'nullable|string|max:5',
            'end_time' => 'nullable|string|max:5',
            'activity_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'person_in_charge' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $maxSort = $rundown->items()->where('day_number', $validated['day_number'])->max('sort_order') ?? 0;

        ScheduleRundownItem::create([
            'schedule_rundown_id' => $rundown->id,
            'schedule_id' => $schedule->id,
            'day_number' => $validated['day_number'],
            'activity_date' => $validated['activity_date'] ?? ($schedule->start_date ? $schedule->start_date->copy()->addDays($validated['day_number'] - 1) : null),
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'activity_name' => $validated['activity_name'],
            'location' => $validated['location'] ?? null,
            'person_in_charge' => $validated['person_in_charge'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order' => $maxSort + 1,
        ]);

        return redirect()->back()
            ->with(['message' => 'Item rundown berhasil ditambahkan!', 'alert-type' => 'success']);
    }

    /**
     * Update rundown item.
     */
    public function updateItem(Request $request, Schedule $schedule, ScheduleRundown $rundown, ScheduleRundownItem $item)
    {
        $validated = $request->validate([
            'day_number' => 'required|integer|min:1',
            'activity_date' => 'nullable|date',
            'start_time' => 'nullable|string|max:5',
            'end_time' => 'nullable|string|max:5',
            'activity_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'person_in_charge' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $item->update($validated);

        return redirect()->back()
            ->with(['message' => 'Item rundown berhasil diperbarui!', 'alert-type' => 'success']);
    }

    /**
     * Delete rundown item.
     */
    public function deleteItem(Schedule $schedule, ScheduleRundown $rundown, ScheduleRundownItem $item)
    {
        $item->delete();

        return redirect()->back()
            ->with(['message' => 'Item rundown berhasil dihapus!', 'alert-type' => 'success']);
    }

    /**
     * Duplicate rundown item.
     */
    public function duplicateItem(Schedule $schedule, ScheduleRundown $rundown, ScheduleRundownItem $item)
    {
        $clone = $item->replicate();
        $maxSort = $rundown->items()->where('day_number', $item->day_number)->max('sort_order') ?? 0;
        $clone->sort_order = $maxSort + 1;
        $clone->activity_name = $item->activity_name . ' (Salinan)';
        $clone->save();

        return redirect()->back()
            ->with(['message' => 'Item rundown berhasil diduplikasi!', 'alert-type' => 'success']);
    }

    /**
     * Reorder items (AJAX).
     */
    public function reorderItems(Request $request, Schedule $schedule, ScheduleRundown $rundown)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:schedule_rundown_items,id',
            'items.*.sort_order' => 'required|integer|min:0',
            'items.*.day_number' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $rundown) {
            foreach ($request->items as $itemData) {
                ScheduleRundownItem::where('schedule_rundown_id', $rundown->id)
                    ->where('id', $itemData['id'])
                    ->update([
                        'sort_order' => $itemData['sort_order'],
                        'day_number' => $itemData['day_number'],
                    ]);
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * View PDF.
     */
    public function pdfView(Schedule $schedule, ScheduleRundown $rundown)
    {
        $rundown->load('items');
        return view('admin.schedules.rundown-pdf', compact('schedule', 'rundown'));
    }

    /**
     * Download PDF.
     */
    public function pdfDownload(Schedule $schedule, ScheduleRundown $rundown)
    {
        $rundown->load('items');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.schedules.rundown-pdf', compact('schedule', 'rundown'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Rundown_' . ($schedule->visitor_name ?? 'Jadwal_' . $schedule->id) . '.pdf';
        return $pdf->download($filename);
    }
}