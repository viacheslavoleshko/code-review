<?php

namespace Modules\Terralab\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTestOriginalResultResource extends JsonResource
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
            'uri' => $this->uri,
        ];
    }
}
