<?php

namespace Modules\Terralab\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Terralab\Entities\PatientLaboratoryOrder;

class SetStatusLaboratoryOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ext_id' => 'max:255|nullable',
            'doc_num' => 'required|max:24|exists:patient_laboratory_orders,order_number',
            'new_status' => ['required', Rule::in(PatientLaboratoryOrder::getAvailableStatuses())],
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
