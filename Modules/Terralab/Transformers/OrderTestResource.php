<?php

namespace Modules\Terralab\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTestResource extends JsonResource
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
            'indicator_id' => $this->indicator_id,
            'name' => $this->name,
            'material' => $this->material,
            'ready' => $this->ready,
            'pdf' => $this->pdf,
            'test_results' => OrderTestResultResource::collection($this->orderTestResults),
            'test_original_results' => OrderTestOriginalResultResource::collection($this->orderTestOriginalResults),
        ];
    }
}
