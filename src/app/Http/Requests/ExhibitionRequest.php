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
            'category_id' => 'required|integer|exists:categories,id',
            'condition_id' => 'required|integer|exists:conditions,id',
            'price' => 'required|integer|min:0',
        ];
    }
}
