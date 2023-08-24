<?php

namespace Modules\Terralab\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PackagesResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'analyses' => $this->packageAnalyses ? PackageAnalysisResource::collection($this->packageAnalyses) : []
        ];
    }
}
