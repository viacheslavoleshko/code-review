<?php

namespace Modules\Indicator\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AnalysHistoryResource extends JsonResource
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
            'date' => $this->date,
            'laboratory' => [
                'laboratory_id' => $this->laboratory_id??null,
                'name' => $this->laboratory->name??null,
            ],
            'original_analys_name' => $this->original_analys_name??null,
            'analys' => [
                'analys_id' => $this->analys_id??null,
                'name' => $this->analys->name??null,     
            ],
            'src_type' => $this->src_type,
            'indicators' => IndicatorHistoryResource::collection($this->indicators)
        ];
    }
}
