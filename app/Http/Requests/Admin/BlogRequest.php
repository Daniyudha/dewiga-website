<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
        switch($this->method()) {
            case 'POST' : {
                return [
                    'title_id' => 'required',
                    'title_en' => 'required',
                    'excerpt_id' => 'required',
                    'excerpt_en' => 'required',
                    'image' => ['required', 'image', 'mimes:png,jpg,jpeg'],
                    'description_id' => 'required',
                    'description_en' => 'required',
                    'category_id' => 'required'
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'title_id' => 'required',
                    'title_en' => 'required',
                    'excerpt_id' => 'required',
                    'excerpt_en' => 'required',
                    'image' => ['image', 'mimes:png,jpg,jpeg'],
                    'description_id' => 'required',
                    'description_en' => 'required',
                    'category_id' => 'required'
                ];
            }
        }
    }
}
