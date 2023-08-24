<?php

namespace Modules\Terralab\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetResultsLaboratoryOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'referral_data' => 'required|array',
            'referral_data.document_data' => 'required|array',
            'referral_data.document_data.ext_id' => 'max:255|nullable',
            'referral_data.document_data.doc_num' => 'required|max:24|nullable|exists:patient_laboratory_orders,order_number',
            'referral_data.test' => 'required|array',
            'referral_data.test.ext_id' => 'nullable|max:255|exists:order_tests,id',
            'referral_data.test.id' => 'max:255|nullable',
            'referral_data.test.code' => 'nullable|max:255|exists:order_tests,indicator_id',
            'referral_data.test.payload' => 'max:255|nullable',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
