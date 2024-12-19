<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'name' => 'required|string|max:120',
            'postcode' => 'required|regex:/^\d{3}-\d{4}$/|size:8',
            'address' => 'required|string|max:120',
            'building' => 'required|string|max:120',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください',
            'name.string' => '文字列で入力してください',
            'name.max' => '120文字以内で入力してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex' => 'XXX-XXXXの形式で入力してください',
            'postcode.size' => 'ハイフンを含めて８文字で入力してください',
            'address.required' => '住所を入力してください',
            'address.string' => '文字列で入力してください',
            'address.max' => '120文字以内で入力してください',
            'building.required' => '住所を入力してください',
            'building.string' => '文字列で入力してください',
            'building.max' => '120文字以内で入力してください',
        ];
    }
}
