<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRundownRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string|max:2000',
            'items' => 'nullable|array',
            'items.*.day_number' => 'required|integer|min:1',
            'items.*.activity_date' => 'nullable|date',
            'items.*.start_time' => 'nullable|string|max:5',
            'items.*.end_time' => 'nullable|string|max:5',
            'items.*.activity_name' => 'required|string|max:255',
            'items.*.location' => 'nullable|string|max:255',
            'items.*.person_in_charge' => 'nullable|string|max:255',
            'items.*.description' => 'nullable|string|max:1000',
            'items.*.sort_order' => 'required|integer|min:0',
        ];
    }
}