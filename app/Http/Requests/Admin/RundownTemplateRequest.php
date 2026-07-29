<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RundownTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'duration_days' => 'required|integer|min:1|max:365',
            'duration_nights' => 'required|integer|min:0|max:364',
            'is_active' => 'boolean',
            'items' => 'nullable|array',
            'items.*.day_number' => 'required|integer|min:1',
            'items.*.start_time' => 'nullable|string|max:5',
            'items.*.end_time' => 'nullable|string|max:5|after_or_equal:items.*.start_time',
            'items.*.activity_name' => 'required|string|max:255',
            'items.*.location' => 'nullable|string|max:255',
            'items.*.person_in_charge' => 'nullable|string|max:255',
            'items.*.description' => 'nullable|string|max:1000',
            'items.*.sort_order' => 'required|integer|min:0',
        ];
    }
}