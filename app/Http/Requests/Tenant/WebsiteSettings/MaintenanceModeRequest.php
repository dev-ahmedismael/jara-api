<?php

namespace App\Http\Requests\Tenant\WebsiteSettings;

use Illuminate\Foundation\Http\FormRequest;

class MaintenanceModeRequest extends FormRequest
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
            'is_enabled' => 'required|boolean',
            'message' => 'string|nullable|max:255',
        ];
    }

    public function messages(): array {
        return [
            'message.max' => 'أدخل رسالة لا تتعدى 255 حرف.'
        ];
    }
}
