<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrder extends FormRequest
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
            'name' => [
                'required',
                'between:2,10',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/(^[\x{4e00}-\x{9fa5}]{1}[\x{4e00}-\x{9fa5}\.·。]{0,8}[\x{4e00}-\x{9fa5}]{0}$)|(^[a-zA-Z]{1}[a-zA-Z\s]{0,8}[a-zA-Z]{0}$)/u', $value)) {
                        return $fail($attribute . 'is invalid.');
                    }
                }
            ],
            'email' => 'required|email|unique:orders',
            'phone' => [
                'required',
                'unique:orders',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^1[3-9]\d{9}$/', $value)) {
                        return $fail($attribute . 'is invalid.');
                    }
                }
            ]
        ];
    }
}
