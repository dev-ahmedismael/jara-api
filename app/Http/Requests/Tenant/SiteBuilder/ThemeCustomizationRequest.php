<?php

namespace App\Http\Requests\Tenant\SiteBuilder;

use Illuminate\Foundation\Http\FormRequest;

class ThemeCustomizationRequest extends FormRequest
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
            'theme_id' => 'string|required',
            'theme_name' => 'string|required',
            'primary_color' => 'string|required',
            'secondary_color' => 'string|required',
            'tertiary_color' => 'string|required',
            'font' => 'string|required',
        ];
    }
}
