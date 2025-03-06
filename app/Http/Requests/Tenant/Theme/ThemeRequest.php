<?php

namespace App\Http\Requests\Tenant\Theme;

use Illuminate\Foundation\Http\FormRequest;

class ThemeRequest extends FormRequest
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
            'theme_id' => 'nullable|string',
            'title' => 'nullable|string',
            'custom_primary_color' => 'nullable|string',
            'custom_secondary_color' => 'nullable|string',
            'custom_tertiary_color' => 'nullable|string',
        ];
    }
}
