<?php

namespace App\Http\Requests\Central\App;

use Illuminate\Foundation\Http\FormRequest;

class AppRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'auth_url' => 'nullable|string',
            'fields' => 'nullable|array',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
        ];
    }
}
