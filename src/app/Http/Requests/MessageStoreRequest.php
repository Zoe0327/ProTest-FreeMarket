<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sold_item_id' => ['required', 'exists:sold_items,id'],
            'message' => ['required', 'string', 'max:400'],
            'message_img_url' => ['nullable', 'mimes:jpeg,png'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => '本文を入力してください',
            'message.max' => '本文は400文字以内で入力してください',
            'message_img_url.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }
}