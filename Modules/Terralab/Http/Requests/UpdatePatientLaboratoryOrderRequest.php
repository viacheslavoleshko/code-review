<?php

namespace Modules\Terralab\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientLaboratoryOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'indicators' => 'required|array',
            'indicators.*.indicator_laboratory_id' => 'required'
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
