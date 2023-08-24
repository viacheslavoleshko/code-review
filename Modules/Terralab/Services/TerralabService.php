<?php

namespace Modules\Terralab\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Modules\Terralab\Entities\OrderTest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Terralab\Entities\PatientLaboratoryOrder;
use Modules\Indicator\DataTransferObjects\ApiInputAnalysHistoryData;
use Modules\Indicator\DataTransferObjects\ApiInputIndicatorHistoryData;
use Modules\Directory\Entities\CatalogLaboratory;
use Modules\Indicator\Services\AnalysHistoryService;

class TerralabService
{
    public $orderTestService;
    public $analysHistoryService;

    public function __construct(OrderTestService $orderTestService, AnalysHistoryService $analysHistoryService)
    {
        $this->orderTestService = $orderTestService;
        $this->analysHistoryService = $analysHistoryService;
    }


    private function getInstance()
    {
        return PatientLaboratoryOrder::with([
            'patient', 'patient.patientInfo',
            'doctor', 'doctor.doctorInfo',
            'laboratory',
            'orderTests'
        ]);
    }

    public function list(User $user)
    {
        return $this->getInstance()
            ->where(['patient_id' => $user->id])
            ->orderBy('created_at', 'desc')
            ->paginate(config('app.items_per_page'));
    }

    public function findPatientLaboratoryOrderById($id)
    {
        return $this->getInstance()->where(['id' => $id])->first();
    }

    public function create(FormRequest $request, User $doctor, User $patient, CatalogLaboratory $laboratory)
    {
        $patientLaboratoryOrderId = DB::transaction(function () use ($request, $doctor, $patient, $laboratory) {
            $patientLaboratoryOrder = PatientLaboratoryOrder::create([
                'patient_id' => $patient->id,
                'laboratory_id' => $laboratory->id,
                'doctor_id' => $doctor->id,
                'menstrphase' => $request->document_data['menstrphase'],
                'descr' => $request->document_data['descr'],

            ]);
            $this->createOrderTests($patientLaboratoryOrder, $request->indicators);
            return $patientLaboratoryOrder->id;
        });

        return $this->findPatientLaboratoryOrderById($patientLaboratoryOrderId);
    }

    public function createOrderTests(PatientLaboratoryOrder $patientLaboratoryOrder, $indicators)
    {
        foreach ($indicators as $indicator) {
            OrderTest::create([
                'order_laboratory_id' => $patientLaboratoryOrder->id,
                'indicator_id' => $indicator['indicator_laboratory_id'],
                'name' => $indicator['name'],
                'material' => $indicator['material'],
            ]);
        }
    }

    private function deleteOrderTests(PatientLaboratoryOrder $patientLaboratoryOrder, $indicators)
    {
        foreach ($indicators as $indicator) {
            $patientLaboratoryOrder->orderTests->where('indicator_id', $indicator['indicator_laboratory_id'])->first()->delete();
        }
    }

    public function createRefferalData(FormRequest $request, PatientLaboratoryOrder $patientLaboratoryOrder, User $doctor, User $patient, CatalogLaboratory $laboratory)
    {
        $referral_data = collect([
            "referral_data" => [
                "document_data" => $request->document_data,
                "patient_data" => [
                    "ext_id" => (string)$patient->id,
                    "full_name" => $patient->patientInfo->first_name . ' ' . $patient->patientInfo->last_name,
                    "sex" => $patient->patientInfo->gender,
                    "birth_date" => $patient->patientInfo->birthday,
                    "phone" => Str::substr($patient->contacts->where('contact_type_id', 1)->first()->content, 0, 13),
                    "email" => $patient->contacts->where('contact_type_id', 2)->first()->content,
                    "passport" => null
                ],
                "doctor_data" => [
                    "ext_id" => (string)$doctor->id,
                    "full_name" => $doctor->doctorInfo?->first_name . ' ' . $doctor?->doctorInfo?->last_name,
                    "phone" => Str::substr($doctor->contacts->where('contact_type_id', 1)->first()->content, 0, 13),
                    "email" => $doctor->contacts->where('contact_type_id', 2)->first()->content,
                ],
                "org_data" => [
                    "ext_id" => config('services.diagen.org_id'),
                    "name" => $laboratory->slug,
                    "email" => null
                ],
                "reception_subdivision" => [
                    "ext_id" => config('services.diagen.subdivision'),
                    "name" => null
                ]
            ]
        ]);

        foreach ($patientLaboratoryOrder->orderTests as $test) {
            $tests[] = [
                "ext_id" => (string)$test->id,
                "id" => null,
                "code" => (string)$test->indicator_id,
                "name" => null,
                "payload" => null,
                "descr" => null,
                "is_cito" => 0
            ];
        }

        $referral_data = $referral_data->map(function ($value) use ($tests) {
            $value['tests'] = $tests;
            return $value;
        })->all();

        return $referral_data;
    }

    public function updateRefferalData(FormRequest $request, PatientLaboratoryOrder $patientLaboratoryOrder)
    {
        $referral_data = collect([
            "referral_data" => [
                "document_data" => [
                    "ext_id" => (string)$patientLaboratoryOrder->id,
                    "doc_num" => (int)$patientLaboratoryOrder->order_number
                ]
            ]
        ]);

        foreach ($request->indicators as $indicator) {
            $test = $patientLaboratoryOrder->orderTests->where('indicator_id', $indicator['indicator_laboratory_id'])->first();
            $add_tests[] = [
                "ext_id" => (string)$test->id,
                "id" => null,
                "code" => (string)$test->indicator_id,
                "name" => null,
                "payload" => null,
                "descr" => null,
                "is_cito" => 0
            ];
        }


        $referral_data = $referral_data->map(function ($value) use ($add_tests) {
            $value['add_tests'] = $add_tests;
            return $value;
        })->all();

        return $referral_data;
    }

    public function refuseRefferalData(FormRequest $request, PatientLaboratoryOrder $patientLaboratoryOrder)
    {
        $referral_data = collect([
            "referral_data" => [
                "document_data" => [
                    "ext_id" => (string)$patientLaboratoryOrder->id,
                    "doc_num" => (int)$patientLaboratoryOrder->order_number
                ]
            ]
        ]);

        foreach ($request->indicators as $indicator) {
            $test = $patientLaboratoryOrder->orderTests->where('indicator_id', $indicator['indicator_laboratory_id'])->first();
            $add_tests[] = [
                "ext_id" => (string)$test->id,
                "id" => null,
                "code" => (string)$test->indicator_id,
                "name" => null,
                "payload" => null,
                "descr" => null,
                "is_cito" => 0
            ];
        }


        $referral_data = $referral_data->map(function ($value) use ($add_tests) {
            $value['add_tests'] = $add_tests;
            return $value;
        })->all();

        $this->deleteOrderTests($patientLaboratoryOrder, $request->indicators);

        return $referral_data;
    }

    public function getByIdRefferalData($indicator_laboratory_id, PatientLaboratoryOrder $patientLaboratoryOrder)
    {
        $test = $patientLaboratoryOrder->orderTests->where('indicator_id', $indicator_laboratory_id)->first();

        $referral_data = collect([
            "referral_data" => [
                "document_data" => [
                    "doc_num" => $patientLaboratoryOrder->order_number
                ],
                "test" => [
                    "ext_id" => (string)$test->id,
                    "id" => null,
                    "code" => (string)$test->indicator_id,
                ]
            ],
        ]);

        return $referral_data;
    }

    public function setNewStatus(FormRequest $request)
    {
        PatientLaboratoryOrder::where('order_number', $request->doc_num)->update([
            'order_status' => $request->new_status,
        ]);
    }

    public function setResults(FormRequest $request, $pdf = false)
    {
        $order = PatientLaboratoryOrder::where('order_number', $request->referral_data['document_data']['doc_num'])?->first();

        if (!empty($request->referral_data['test']['ext_id'])) {
            $test = OrderTest::where('id', $request->referral_data['test']['ext_id'])->first();
        } elseif (!empty($request->referral_data['test']['code'])) {
            $test = $order->orderTests->where('indicator_id', $request->referral_data['test']['code'])->first();
        }

        $getByIdRefferalData = $this->getByIdRefferalData($test->indicator_id, $order);
        $response = Http::post(config('services.diagen.diagen_base_url') . config('services.diagen.get_by_test_id_endpoint') , $getByIdRefferalData->toArray());
        if($response['data']['test_results_status'] == 'READY' && $pdf == false) {
            $test_results = $response['data']['test_results'];
            $testResultSaved = $this->orderTestService->saveResult($test, $test_results);


            $indicators = [];
            foreach ($test_results as $item) {
                $indicators[] = new ApiInputIndicatorHistoryData($item['indicator_name'], $item['norm_text'], $item['ubnormal_flag'], $item['result'], $item['unit_name']);
            }
            $historyData = new ApiInputAnalysHistoryData(
                $order->patient_id,
                CatalogLaboratory::find(2)->id,
                date("Y-m-d H:i:s"),
                $test_results[0]['test_name'],
                ApiInputIndicatorHistoryData::collection($indicators)
            ); 

            $apiAnalysHistory = $this->analysHistoryService->createApiAnalysHistory($historyData);

            $test->update([
                'ready' => true
            ]);

        }

        if($pdf == true) {
            if($response['data']['form_original'][0]['status'] == 'READY') {
                $test_original_results = $response['data']['form_original'];
                $testOriginalResultSaved = $this->orderTestService->saveOriginalResult($test, $test_original_results);

                $test->update([
                    'pdf' => true
                ]);
            }
        }
    }

    public function getStaticData()
    {
        return [
            'terralab' => PatientLaboratoryOrder::getStatic(),
        ];
    }
}
