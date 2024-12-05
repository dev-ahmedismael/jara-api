<?php

namespace App\Http\Requests\Tenant\Consultation;

use Illuminate\Foundation\Http\FormRequest;

class ConsultationRequest extends FormRequest
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
            'title' => 'string|required',
            'description' => 'string|required',
            'type' => 'required|string',
            'images' => 'array|nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'start_date' => 'date|nullable',
            'end_date' => 'date|nullable',
            'booking_last_date' => 'date|nullable',
            'price' => 'nullable|numeric',
            'enable_comments' => 'required',
            'enable_rates' => 'required',
            'status' => 'string',
            'form_fields' => 'nullable',
        ];
    }
}
