<?php

namespace App\Http\Requests\Tenant\Consultation;

use Illuminate\Foundation\Http\FormRequest;

class ConsultationUpdateRequest extends FormRequest
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
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'images' => 'array|nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'start_date' => 'date_format:Y-m-d H:i:s|nullable',
            'end_date' => 'date_format:Y-m-d H:i:s|nullable',
            'booking_last_date' => 'date_format:Y-m-d H:i:s|nullable',
            'price' => 'nullable|numeric',
            'enable_comments' => 'boolean',
            'enable_rates' => 'boolean',
            'status' => 'nullable|string',
            'form' => 'nullable|array',
        ];
    }
}
