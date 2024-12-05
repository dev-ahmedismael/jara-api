<?php

namespace App\Http\Requests\Tenant\WebSettings;

use Illuminate\Foundation\Http\FormRequest;

class BasicInfoRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|min:9|max:20',
            'logo' => 'nullable',
            'icon' => 'nullable',
        ];
    }
}
