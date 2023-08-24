<?php

namespace Modules\Terralab\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Terralab\Entities\PackageAnalysis;

class PackageAnalysisResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'analysis' => $this->analysis,
            'gender' => PackageAnalysis::GENDER_TYPE[$this->analysis['code']] ?? 'any'
        ];
    }
}
