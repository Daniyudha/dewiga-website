<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TravelPackageRequest extends FormRequest
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
            'type_id' => 'required',
            'type_en' => 'required',
            'location_id' => 'required',
            'location_en' => 'required',
            'price' => 'required',
            'description_id' => 'required',
            'description_en' => 'required',
            'is_signature' => 'sometimes|in:0,1'
        ];
    }
}