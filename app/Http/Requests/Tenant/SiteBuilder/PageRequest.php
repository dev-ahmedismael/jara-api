<?php

namespace App\Http\Requests\Tenant\SiteBuilder;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
            'slug' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'diplay_in_navbar' => 'boolean',
            'display_in_footer' => 'boolean',
        ];
    }
}
