<?php

namespace Modules\Indicator\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAnalysHistoryRequest extends FormRequest
{    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'laboratory_id' => 'required|exists:catalog_laboratories,id',
            'date' => 'required|date_format:Y-m-d',
            'analys_id' => 'required|exists:catalog_analysis,id',
            'indicators' => 'required|array',
            'indicators.*.indicator_id' => 'required|distinct|exists:catalog_indicators,id',
            'indicators.*.original_norm_text' => 'nullable|string',
            'indicators.*.norm_flag' => 'nullable|in:0,1',
            'indicators.*.result' => 'required',
            'indicators.*.measure_type_id' => 'nullable|exists:catalog_measure_types,id',
           
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
