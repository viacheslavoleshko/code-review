<?php

namespace Modules\Indicator\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Directory\Transformers\CatalogIndicatorResource;

class AnalysGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return new CatalogIndicatorResource($this->indicator);
    }
}
