<?php

namespace Modules\Indicator\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Indicator\Entities\AnalysGroup;

class CreateAnalysGroupRequest extends FormRequest
{

    public function prepareForValidation()
    {
        if (AnalysGroup::where(['analys_id' => $this->catalogAnalys->id])->exists()) {
            throw ValidationException::withMessages(['catalogAnalys' => 'Analys group for this id is exists']);            
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'indicators' => 'required|array',
            'indicators.*.indicator_id' => ['required', 'exists:catalog_indicators,id']
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
