<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RundownTemplateRequest;
use App\Models\RundownTemplate;
use App\Models\RundownTemplateItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RundownTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function index(Request $request)
    {
        $query = RundownTemplate::with('items', 'creator')->withCount('items');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $templates = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.rundown-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.rundown-templates.create');
    }

    public function store(RundownTemplateRequest $request)
    {
        $data = $request->validated();

        $template = DB::transaction(function () use ($data) {
            $template = RundownTemplate::create([
                'name' => $data['name'],
                'code' => $data['code'] ?? null,
                'description' => $data['description'] ?? null,
                'duration_days' => $data['duration_days'],
                'duration_nights' => $data['duration_nights'] ?? 0,
                'is_active' => $data['is_active'] ?? true,
                'created_by' => auth()->id(),
            ]);

            if (!empty($data['items'])) {
                foreach ($data['items'] as $itemData) {
                    $template->items()->create([
                        'day_number' => $itemData['day_number'],
                        'start_time' => $itemData['start_time'] ?? null,
                        'end_time' => $itemData['end_time'] ?? null,
                        'activity_id' => $itemData['activity_id'] ?? null,
                        'activity_name' => $itemData['activity_name'],
                        'location' => $itemData['location'] ?? null,
                        'person_in_charge' => $itemData['person_in_charge'] ?? null,
                        'description' => $itemData['description'] ?? null,
                        'sort_order' => $itemData['sort_order'] ?? 0,
                    ]);
                }
            }

            return $template;
        });

        return redirect()->route('admin.rundown-templates.show', $template)
            ->with(['message' => 'Template rundown berhasil dibuat!', 'alert-type' => 'success']);
    }

    public function show(RundownTemplate $rundownTemplate)
    {
        $rundownTemplate->load('items', 'creator');
        $groupedItems = $rundownTemplate->items->groupBy('day_number');
        return view('admin.rundown-templates.show', compact('rundownTemplate', 'groupedItems'));
    }

    public function edit(RundownTemplate $rundownTemplate)
    {
        $rundownTemplate->load('items');
        return view('admin.rundown-templates.edit', compact('rundownTemplate'));
    }

    public function update(RundownTemplateRequest $request, RundownTemplate $rundownTemplate)
    {
        $data = $request->validated();

        DB::transaction(function () use ($rundownTemplate, $data) {
            $rundownTemplate->update([
                'name' => $data['name'],
                'code' => $data['code'] ?? null,
                'description' => $data['description'] ?? null,
                'duration_days' => $data['duration_days'],
                'duration_nights' => $data['duration_nights'] ?? 0,
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Delete existing items and recreate
            $rundownTemplate->items()->delete();

            if (!empty($data['items'])) {
                foreach ($data['items'] as $itemData) {
                    $rundownTemplate->items()->create([
                        'day_number' => $itemData['day_number'],
                        'start_time' => $itemData['start_time'] ?? null,
                        'end_time' => $itemData['end_time'] ?? null,
                        'activity_id' => $itemData['activity_id'] ?? null,
                        'activity_name' => $itemData['activity_name'],
                        'location' => $itemData['location'] ?? null,
                        'person_in_charge' => $itemData['person_in_charge'] ?? null,
                        'description' => $itemData['description'] ?? null,
                        'sort_order' => $itemData['sort_order'] ?? 0,
                    ]);
                }
            }
        });

        return redirect()->route('admin.rundown-templates.show', $rundownTemplate)
            ->with(['message' => 'Template rundown berhasil diperbarui!', 'alert-type' => 'success']);
    }

    public function destroy(RundownTemplate $rundownTemplate)
    {
        $rundownTemplate->delete();

        return redirect()->route('admin.rundown-templates.index')
            ->with(['message' => 'Template rundown berhasil dihapus!', 'alert-type' => 'success']);
    }

    public function toggleActive(RundownTemplate $rundownTemplate)
    {
        $rundownTemplate->update(['is_active' => !$rundownTemplate->is_active]);

        return redirect()->back()
            ->with(['message' => 'Status template diperbarui!', 'alert-type' => 'success']);
    }

    public function duplicate(RundownTemplate $rundownTemplate)
    {
        $clone = $rundownTemplate->duplicate();

        return redirect()->route('admin.rundown-templates.show', $clone)
            ->with(['message' => 'Template berhasil diduplikasi!', 'alert-type' => 'success']);
    }

    public function reorderItems(Request $request, RundownTemplate $rundownTemplate)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:rundown_template_items,id',
            'items.*.sort_order' => 'required|integer|min:0',
            'items.*.day_number' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $rundownTemplate) {
            foreach ($request->items as $itemData) {
                RundownTemplateItem::where('rundown_template_id', $rundownTemplate->id)
                    ->where('id', $itemData['id'])
                    ->update([
                        'sort_order' => $itemData['sort_order'],
                        'day_number' => $itemData['day_number'],
                    ]);
            }
        });

        return response()->json(['success' => true]);
    }
}