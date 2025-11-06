<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'profile_img_url' => 'nullable|image|mimes:jpeg,png',
            'name' => 'required|string|max:20',
            'post_code' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'profile_img_url.mimes' => '画像ファイルはJPEGまたはPNG形式を選択してください。',
            'name.required' => 'ユーザー名を入力してください。',
            'name.string' => 'ユーザー名には文字列を入力してください。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',
            'post_code.required' => '郵便番号を入力してください。',
            'post_code.regex' => 'ハイフンありの8文字で入力してください。',
            'address.required' => '住所を入力してください。',
        ];
    }
}
