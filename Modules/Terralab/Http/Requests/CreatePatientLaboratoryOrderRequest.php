<?php

namespace Modules\Terralab\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Terralab\Entities\PatientLaboratoryOrder;

class CreatePatientLaboratoryOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'document_data' => 'required|array',
            'document_data.ext_id' => 'max:255|nullable',
            'document_data.doc_num' => 'max:24|nullable',
            'document_data.descr' => 'max:255|nullable',
            'document_data.preg_week' => 'max:255|nullable',
            'document_data.menstrphase' => ['nullable', Rule::in(array_keys(PatientLaboratoryOrder::SELECTOR_DATA['menstrphases'])),
            ],
            'document_data.diagnosis_code' => 'max:255|nullable',
            'document_data.policy_num' => 'max:255|nullable',
            'indicators' => 'required|array',
            'indicators.*.indicator_laboratory_id' => 'required|numeric',
            'indicators.*.name' => 'required|string|max:255',
            'indicators.*.material' => 'required|string|max:255',
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
