<?php

namespace App\Http\Requests\Api\CurrencyLayer;

use Illuminate\Foundation\Http\FormRequest;

class ConvertRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'currencies' => 'required|array|max:5',
            'amount' =>'required|numeric|min:0'
        ];
    }
}
