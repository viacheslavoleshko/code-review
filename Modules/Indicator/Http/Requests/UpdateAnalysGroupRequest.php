<?php

namespace Modules\Indicator\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Indicator\Entities\AnalysGroup;

class UpdateAnalysGroupRequest extends FormRequest
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
