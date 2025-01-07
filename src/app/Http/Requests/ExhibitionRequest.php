<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'item_name' => 'required',
            'description' => 'required|max:255',
            'item_image' => 'required|mimes:png,jpeg',
            'category' => 'required',
            'condition' => 'required',
            'price' => 'required|numeric|min:0|',
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'email.required' => 'メールアドレスを入力してください',
    //         'email.email' => 'メール形式で入力してください',
    //         'password.required' => 'パスワードを入力してください',
    //         'password.min' => '8文字以上で入力してください',
    //         'password.alpha_dash' => '半角英数字で入力してください',
    //     ];
    // }
}
