<?php

namespace App\Http\Requests\Tenant\WebsiteSettings;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
            'name' => 'nullable|string',
            'path' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'videos' => 'nullable|array',
            'videos.*' => 'nullable|url',
        ];
    }

    public function messages(): array {
        return [
            'images.*.max' => 'حجم كل صورة يجب ألا يتجاوز 2 ميجا بايت.',
            'images.*.image' => 'يجب أن يكون الملف المرفق صورة صحيحة.',
            'images.*.mimes' => 'الصورة يجب أن تكون بصيغة jpeg, png, jpg, gif, أو svg.',
            'videos.*.url' => 'يجب أن يكون الفيديو رابطًا صحيحًا.',
        ];
    }
}
