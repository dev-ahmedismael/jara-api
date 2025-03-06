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
            'type' => 'string|required',
            'image' => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'string|required',
            'description' => 'string|required',
            'start_date' => 'date|nullable',
            'start_time' => 'date_format:H:i|nullable',
            'end_date' => 'date|nullable|',
            'end_time' => 'date_format:H:i|nullable',
            'expiry_date' => 'date|nullable',
            'expiry_time' => 'date_format:H:i|nullable',
            'price' => 'required|numeric',
            'enable_comments' => 'boolean|required',
            'enable_rating' => 'boolean|required',
            'max_allowed_bookings' => 'nullable|numeric',
        ];
    }
}
