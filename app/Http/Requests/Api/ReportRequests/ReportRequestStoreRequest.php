<?php

namespace App\Http\Requests\Api\ReportRequests;

use App\Models\ReportRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportRequestStoreRequest extends FormRequest
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
            'currencies' => 'required|string',
            'amount' =>'required|numeric|min:0',
            'type' => [
                'required',
                Rule::in([ReportRequest::ONE_YEAR, ReportRequest::SIX_MONTH, ReportRequest::ONE_MONTH]),
            ]
        ];
    }
}
