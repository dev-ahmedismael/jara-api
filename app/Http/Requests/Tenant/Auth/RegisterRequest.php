<?php

namespace App\Http\Requests\Tenant\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'domain' => 'required | string | max:255',
            'name' => 'required | string | max:255',
            'job' => 'nullable | string | max:255',
            'phone' => 'required | string | max:20',
            'email' => 'required | string | max:255 | email',
            'password' => 'required | string | max:255',
            'company_name_ar' => 'nullable | string | max:255',
            'company_name_en' => 'nullable | string | max:255',
            'license_number' => 'nullable | string | max:255',
            'license_document' => 'nullable | file',
        ];
    }
}
