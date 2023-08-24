<?php

namespace Modules\Indicator\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ValidateIndicatorHistoryRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'indicator_id' => [
                'required', 'exists:catalog_indicators,id',
                Rule::unique('indicator_history')->where(function ($query) {
                    return $query->where('history_id', $this->indicatorHistory->history_id)
                        ->where('indicator_id', $this->indicator_id);
                })
            ],
            'norm_flag' => 'required|in:0,1',
            'measure_type_id' =>  'nullable|exists:catalog_measure_types,id'
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
