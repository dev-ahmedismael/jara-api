<?php

namespace App\Http\Requests\Tenant\Promocode;

use Illuminate\Foundation\Http\FormRequest;

class PromocodeRequest extends FormRequest
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
            'code' => 'required|string|unique:promocodes,code,' . $this->route('promocode'),
            'discount_type' => 'required|string',
            'discount_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];
    }

    public function messages(): array {
        return [
            'code.unique' => 'الرمز الذي أدخلته مستخدم من قبل.',
            'end_date.after' => 'تاريخ الإنتهاء يجب أن يكون لاحق لتاريخ البداية.',
            'discount_amount.min' => 'أدخل قيمة أكبر من الصفر.'
        ];
    }
}
