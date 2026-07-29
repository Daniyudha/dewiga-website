<?php

namespace App\Services;

use App\Models\RundownTemplate;
use App\Models\Schedule;
use App\Models\ScheduleRundownItem;
use Illuminate\Support\Facades\DB;

class RundownService
{
    /**
     * Copy a template's items to a schedule.
     */
    public function copyTemplateToSchedule(Schedule $schedule, int $templateId): array
    {
        $template = RundownTemplate::with('items')->findOrFail($templateId);

        return DB::transaction(function () use ($schedule, $template) {
            // Clear existing rundown items
            $schedule->rundownItems()->delete();

            $items = [];
            foreach ($template->items as $templateItem) {
                $item = ScheduleRundownItem::create([
                    'schedule_id' => $schedule->id,
                    'day_number' => $templateItem->day_number,
                    'activity_date' => $schedule->start_date->copy()->addDays($templateItem->day_number - 1),
                    'start_time' => $templateItem->start_time,
                    'end_time' => $templateItem->end_time,
                    'activity_name' => $templateItem->activity_name,
                    'location' => $templateItem->location,
                    'description' => $templateItem->description,
                    'sort_order' => $templateItem->sort_order,
                ]);
                $items[] = $item;
            }

            return $items;
        });
    }

    /**
     * Add a single item to schedule rundown.
     */
    public function addItem(Schedule $schedule, array $data): ScheduleRundownItem
    {
        $maxSort = $schedule->rundownItems()->max('sort_order') ?? 0;

        return ScheduleRundownItem::create([
            'schedule_id' => $schedule->id,
            'day_number' => $data['day_number'] ?? 1,
            'activity_date' => $data['activity_date'] ?? $schedule->start_date,
            'start_time' => $data['start_time'] ?? null,
            'end_time' => $data['end_time'] ?? null,
            'activity_name' => $data['activity_name'],
            'location' => $data['location'] ?? null,
            'person_in_charge' => $data['person_in_charge'] ?? null,
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? ($maxSort + 1),
        ]);
    }

    /**
     * Update a rundown item.
     */
    public function updateItem(ScheduleRundownItem $item, array $data): ScheduleRundownItem
    {
        $item->update($data);
        return $item->fresh();
    }

    /**
     * Reorder items (swap sort_order).
     */
    public function reorderItems(Schedule $schedule, array $order): void
    {
        DB::transaction(function () use ($schedule, $order) {
            foreach ($order as $id => $sortOrder) {
                ScheduleRundownItem::where('schedule_id', $schedule->id)
                    ->where('id', $id)
                    ->update(['sort_order' => $sortOrder]);
            }
        });
    }

    /**
     * Get all templates for selection.
     */
    public function getTemplates()
    {
        return RundownTemplate::where('is_active', true)->withCount('items')->get();
    }
}