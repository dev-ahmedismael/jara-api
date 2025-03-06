<?php

namespace App\Http\Requests\Tenant\WebsiteSettings;

use Illuminate\Foundation\Http\FormRequest;

class SocialMediaUrlRequest extends FormRequest
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
            'facebook' => 'nullable|url',
            'x' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'youtube' => 'nullable|url',
            'telegram' => 'nullable|url',
            'snapchat' => 'nullable|url',
            'tiktok' => 'nullable|url',
        ];
    }

    public function messages(): array {
        return [
            'facebook.url' => 'أدخل رابط صالح مثل: https://www.facebook.com/your-username',
            'x.url' => 'أدخل رابط صالح مثل: https://x.com/your-username',
            'instagram.url' => 'أدخل رابط صالح مثل: https://instagram.com/your-username',
            'linkedin.url' => 'أدخل رابط صالح مثل: https://www.linkedin.com/in/your-username',
            'youtube.url' => 'أدخل رابط صالح مثل: https://www.youtube.com/@your-username',
            'telegram.url' => 'أدخل رابط صالح مثل: https://your-username.t.me',
            'snapchat.url' => 'أدخل رابط صالح مثل: https://www.snapchat.com/your-username',
            'tiktok.url' => 'أدخل رابط صالح مثل: https://www.tiktok.com/@your-username',
        ];
    }
}
