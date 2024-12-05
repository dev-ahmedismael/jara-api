<?php

namespace App\Http\Requests\Tenant\Shared;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
        $rules = [];

        foreach ($this->all() as $filter => $value) {
            if (is_string($value)) {
                // Text
                $rules[$filter] = 'string|max:255';
            } elseif (is_array($value) && isset($value['from'], $value['to'])) {
                if (strtotime($value['from']) !== false && strtotime($value['to']) !== false) {
                    // Dates
                    $rules["{$filter}.from"] = 'nullable|date';
                    $rules["{$filter}.to"] = 'nullable|date|after_or_equal:' . $value['from'];
                } elseif (is_numeric($value['from']) && is_numeric($value['to'])) {
                    // Numbers
                    $rules["{$filter}.from"] = 'nullable|numeric';
                    $rules["{$filter}.to"] = 'nullable|numeric|gte:' . $value['from'];
                }
            }
        }

        return $rules;
    }
}
