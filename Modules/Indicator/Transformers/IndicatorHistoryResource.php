<?php

namespace Modules\Indicator\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Directory\Transformers\CatalogIndicatorResource;

class IndicatorHistoryResource extends JsonResource
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
            'original_indicator_name' => $this->original_indicator_name,
            'indicator' => $this->catalogIndicator->name ?? null,
            'original_norm_text' => $this->original_norm_text,
            'etalon_norm_text' => $this->catalogIndicator->etalon_norm_text??null,
            'norm_flag' => $this->norm_flag,
            'result' => $this->result,
            'original_measure_type' => $this->original_measure_type,
            'measure_type' => $this->measureType->name??null,
            'etalon_measure_type' => $this->catalogIndicator->etalonMeasureType->name??null,
            'validated_at' => $this->validated_at,
            'validated_who' => $this->validated_who,            
            'probably_indicators' => CatalogIndicatorResource::collection($this->probablyIndicators) //this field auto fill from alias service
        ];
    }
}
