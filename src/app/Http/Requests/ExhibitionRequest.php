<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'required|string|max:255',
            'item_img_url' => 'required|image|mimes:jpeg,png',
            'category_id' => 'required|array',
            'category_id.*' => 'integer|exists:categories,id',
            'condition_id' => 'required|integer|exists:conditions,id',
            'price' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください。',
            'name.string' => '商品名には文字列を入力してください。',

            'description.required' => '商品の説明を入力してください。',
            'description.string' => '商品説明には文字列を入力してください。',
            'description.max' => '商品説明は255文字以内で入力してください。',

            'item_img_url.required' => '画像を選択してください。',
            'item_img_url.image' => '有効な画像ファイルを選択してください。',
            'item_img_url.mimes' => '画像ファイルはJPEGまたはPNG形式を選択してください。',

            'category_id.required' => 'カテゴリーを1つ以上選択してください。',
            'category_id.array' => 'カテゴリーの選択形式が不正です。',
            'category_id.*.integer' => '選択されたカテゴリーの値が不正です。',
            'category_id.*.exists' => '存在しないカテゴリーが選択されています。',

            'condition_id.required' => '商品の状態を選択してください。',

            'price.required' => '販売価格を入力してください。',
            'price.integer' => '販売価格は整数で入力してください。',
            'price.min' => '販売価格は0円以上に設定してください',
        ];
    }
}
