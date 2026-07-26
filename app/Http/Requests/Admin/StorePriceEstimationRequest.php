<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePriceEstimationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'arrival_date' => 'required|date',
            'departure_date' => 'required|date|after_or_equal:arrival_date',
            'student_count' => 'required|integer|min:1',
            'companion_count' => 'required|integer|min:0',
            'service_participant_count' => 'required|integer|min:1',
            'activity_participant_count' => 'required|integer|min:0',
            'live_in_nights' => 'nullable|integer|min:0',
            'meal_count' => 'nullable|integer|min:0',
            'regular_activity_count' => 'nullable|integer|min:0',
            'art_sessions' => 'nullable|integer|min:0',

            // Snack (jumlah kali, harga dari master)
            'snack_count' => 'nullable|integer|min:0',

            // Cooking competition
            'cooking_active' => 'nullable|boolean',
            'cooking_participants' => 'nullable|integer|min:0',
            'cooking_capacity' => 'nullable|integer|min:1',
            'cooking_price_per_group' => 'nullable|numeric|min:0',
            'cooking_manual_groups' => 'nullable|integer|min:0',

            // Pickup
            'pickup_active' => 'nullable|boolean',
            'pickup_users' => 'nullable|integer|min:0',
            'pickup_manual_units' => 'nullable|integer|min:0',

            // Cultural performance
            'cultural_performances' => 'nullable|integer|min:0',

            // Sound & Lighting
            'sound_lighting_option' => 'nullable|in:none,sound_only,lighting_only,package',

            // Live music
            'live_music_performances' => 'nullable|integer|min:0',

            // Other addon (legacy)
            'other_addon_active' => 'nullable|boolean',
            'other_addon_name' => 'nullable|string|max:255',
            'other_addon_price' => 'nullable|numeric|min:0',
            'other_addon_quantity' => 'nullable|integer|min:1',

            // Custom add-on items (dynamic array)
            'custom_items' => 'nullable|array',
            'custom_items.*.name' => 'required_with:custom_items|string|max:255',
            'custom_items.*.quantity' => 'nullable|integer|min:1',
            'custom_items.*.frequency' => 'nullable|integer|min:1',
            'custom_items.*.unit' => 'nullable|string|max:50',
            'custom_items.*.unit_price' => 'nullable|numeric|min:0',
            'custom_items.*.description' => 'nullable|string|max:500',

            // Rounding
            'rounding_type' => 'nullable|in:none,up_1000,up_5000,up_10000,down_1000,down_5000,down_10000',

            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'institution_name.required' => 'Nama sekolah atau instansi wajib diisi.',
            'contact_person.required' => 'Nama penanggung jawab wajib diisi.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'arrival_date.required' => 'Tanggal kedatangan wajib diisi.',
            'departure_date.required' => 'Tanggal kepulangan wajib diisi.',
            'departure_date.after_or_equal' => 'Tanggal kepulangan tidak boleh sebelum tanggal kedatangan.',
            'student_count.required' => 'Jumlah siswa wajib diisi.',
            'student_count.min' => 'Jumlah siswa minimal 1.',
            'service_participant_count.required' => 'Jumlah peserta layanan utama wajib diisi.',
            'service_participant_count.min' => 'Jumlah peserta layanan utama minimal 1.',
            'activity_participant_count.required' => 'Jumlah peserta kegiatan wajib diisi.',
            'snack_count.required_if' => 'Jumlah snack wajib diisi ketika komponen snack diaktifkan.',
            'snack_count.min' => 'Jumlah snack minimal 1 jika snack diaktifkan.',
            'other_addon_name.required_if' => 'Nama add-on lainnya wajib diisi jika add-on aktif.',
            'other_addon_price.min' => 'Harga tidak boleh negatif.',
            'cooking_price_per_group.min' => 'Harga lomba masak tidak boleh negatif.',
            'custom_items.*.name.required_with' => 'Nama item tambahan wajib diisi.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Auto-calculate service_participant_count
        if ($this->has('student_count') && $this->has('companion_count') && !$this->has('service_participant_count')) {
            $this->merge([
                'service_participant_count' => (int) $this->student_count + (int) $this->companion_count,
            ]);
        }

        // Auto-fill activity_participant_count
        $activityCount = $this->input('activity_participant_count');
        if ($this->has('student_count') && (!$this->has('activity_participant_count') || $activityCount === '' || $activityCount === null || $activityCount === '0' || $activityCount === 0)) {
            $this->merge([
                'activity_participant_count' => (int) $this->student_count,
            ]);
        }

        // Ensure booleans
        $this->merge([
            'cooking_active' => $this->boolean('cooking_active'),
            'pickup_active' => $this->boolean('pickup_active'),
            'other_addon_active' => $this->boolean('other_addon_active'),
        ]);

        // Filter out empty custom items
        if ($this->has('custom_items') && is_array($this->custom_items)) {
            $filtered = array_filter($this->custom_items, function ($item) {
                return !empty(trim($item['name'] ?? '')) && (float) ($item['unit_price'] ?? 0) > 0;
            });
            $this->merge([
                'custom_items' => array_values($filtered),
            ]);
        }
    }
}