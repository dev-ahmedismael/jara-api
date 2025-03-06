<?php

namespace App\Http\Requests\Tenant\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        $customer = $this->route('customer');

        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15', "unique:customers,phone,{$customer}"],
            'email' => ['required', 'email', 'max:255', "unique:customers,email,{$customer}"],
            'password' => $this->isMethod('post') ? ['required', 'string', 'min:6'] : ['sometimes', 'nullable', 'string',
        ]];
    }
}
