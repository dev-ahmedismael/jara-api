<?php

namespace App\Http\Requests\Tenant\SiteBuilder;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
            'page_id' => 'required|integer|exists:pages,id',
            'index' => 'required|integer',
            'type' => 'required|string|max:255',
            'properties' => 'nullable|array',
        ];
    }
}
