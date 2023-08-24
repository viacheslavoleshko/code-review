<?php

namespace Modules\Terralab\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\PersonalData\Transformers\PatientInfoResource;
use Modules\DoctorAppointment\Transformers\DoctorInfoResource;

class PatientLaboratoryOrderResource extends JsonResource
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
            'menstrphase' => $this->menstrphase,
            'descr' => $this->descr,
            'patient' => [
                'first_name' => $this->patient->patientInfo->first_name,
                'last_name' => $this->patient->patientInfo->last_name,
                'middle_name' => $this->patient->patientInfo->middle_name,
            ],
            'doctor' => [
                'first_name' => $this->doctor->doctorInfo->first_name,
                'last_name' => $this->doctor->doctorInfo->last_name,
                'middle_name' => $this->doctor->doctorInfo->middle_name,
            ],
            'laboratory' => [
                'laboratory_id' => $this->laboratory_id,
                'name' => $this->laboratory->name,
            ],
            'order_number' => $this->order_number,
            'order_status' => $this->order_status,
            'indicators' => OrderTestResource::collection($this->orderTests),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
