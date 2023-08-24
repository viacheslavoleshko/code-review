<?php

namespace Modules\Terralab\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTestResultResource extends JsonResource
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
            'order_test_id'=> $this->order_test_id,
            'material_name' => $this->material_name,
            'test_name' => $this->test_name,
            'test_code' => $this->test_code,
            'gis' => $this->gis,
            'indicator_name' => $this->indicator_name,
            'unit_name' => $this->unit_name,
            'result' => $this->result,
            'norm_text' => $this->norm_text,
            'ubnormal_flag' => $this->ubnormal_flag,
            'payload' => $this->payload,
            'order' => $this->order,
            'date_ready' => $this->date_ready,
            'done_employee_name' => $this->done_employee_name,
            'done_employee_id' => $this->done_employee_id,
            'done_employee_post' => $this->done_employee_post,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
