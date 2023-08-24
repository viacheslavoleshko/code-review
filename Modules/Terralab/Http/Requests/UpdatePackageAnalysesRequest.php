<?php

namespace Modules\Terralab\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageAnalysesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'analyses' => 'nullable|array',
            'analyses.*' => 'nullable|array',
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
