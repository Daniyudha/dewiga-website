<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $scheduleId = $this->route('schedule')?->id ?? 'NULL';

        return [
            'travel_package_id' => 'required|exists:travel_packages,id',
            'type' => 'required|in:open_trip,confirmed,pending',
            'start_date' => [
                'required',
                'date',
                // Unique per travel_package + start_date combination
                'unique:schedules,start_date,' . $scheduleId . ',id,travel_package_id,' . $this->travel_package_id,
            ],
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'visitor_name' => 'nullable|string|max:255',
            'quota' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'travel_package_id.required' => 'Pilih paket wisata',
            'travel_package_id.exists' => 'Paket wisata tidak valid',
            'type.required' => 'Pilih tipe jadwal',
            'type.in' => 'Tipe jadwal tidak valid',
            'start_date.required' => 'Tanggal mulai wajib diisi',
            'start_date.date' => 'Tanggal mulai tidak valid',
            'start_date.unique' => 'Jadwal untuk paket ini pada tanggal tersebut sudah ada',
            'end_date.date' => 'Tanggal selesai tidak valid',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
            'quota.required' => 'Kuota wajib diisi',
            'quota.integer' => 'Kuota harus berupa angka',
            'quota.min' => 'Kuota minimal 1',
        ];
    }
}
