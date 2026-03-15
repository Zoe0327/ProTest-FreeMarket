<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'sending_postcode' => 'required|string|size:8',
            'sending_address' => 'required|string|max:255',
            'sending_building' => 'nullable|string|max:255',
            'payment_method' => 'required|string',
        ];
    }
}
