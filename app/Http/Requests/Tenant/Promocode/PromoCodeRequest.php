<?php

namespace App\Http\Requests\Tenant\Promocode;

use Illuminate\Foundation\Http\FormRequest;

class PromoCodeRequest extends FormRequest
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
            'code' => 'string|required',
            'discount' => 'numeric|required',
            'discount_type' => 'string|required|in:percentage,value',
            'start_date' => 'date|required',
            'end_date' => 'date|required',
        ];
    }
}
