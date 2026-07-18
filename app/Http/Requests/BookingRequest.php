<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'institution' => 'nullable',
            'email' => 'required|email',
            'number_phone' => 'required',
            'schedule_id' => 'nullable|exists:schedules,id',
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'travel_package_id' => 'required',
            'people_count' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'participants' => 'nullable|array',
            'participants.*.name' => 'required|string|max:255',
            'participants.*.email' => 'nullable|email',
            'participants.*.phone' => 'nullable|string',
        ];
    }
}