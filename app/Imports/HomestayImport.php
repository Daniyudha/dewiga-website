<?php

namespace App\Imports;

use App\Models\Homestay;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class HomestayImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Homestay([
            'name_id' => $row['name_id'] ?? '',
            'name_en' => $row['name_en'] ?? '',
            'address_id' => $row['address_id'] ?? '',
            'address_en' => $row['address_en'] ?? '',
            'latitude' => $row['latitude'] ?? null,
            'longitude' => $row['longitude'] ?? null,
            'capacity' => $row['capacity'] ?? null,
            'is_active' => $row['is_active'] ?? false,
            'order' => $row['order'] ?? 0,
        ]);
    }

    public function rules(): array
    {
        return [
            'name_id' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'address_id' => 'nullable|string|max:255',
            'address_en' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'capacity' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ];
    }
}