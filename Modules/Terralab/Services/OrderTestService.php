<?php

namespace Modules\Terralab\Services;

use Illuminate\Support\Facades\DB;
use Modules\Terralab\Entities\OrderTest;
use Modules\Terralab\Entities\OrderTestResult;
use Modules\Terralab\Entities\PatientLaboratoryOrder;

class OrderTestService
{
    private function getInstance()
    {
        return OrderTest::with([
            'orderTestResults'
        ]);
    }

    public function list(PatientLaboratoryOrder $patientLaboratoryOrder)
    {
        return $this->getInstance()
            ->where(['order_laboratory_id' => $patientLaboratoryOrder->id])
            ->orderBy('created_at', 'desc')
            ->paginate(config('app.items_per_page'));
    }

    public function findOrderTestById($id)
    {
        return $this->getInstance()->where(['id' => $id])->first();
    }

    public function saveResult($test, $test_results)
    {
        $orderTestId = DB::transaction(function () use ($test, $test_results) {
            foreach ($test_results as $test_result) {
                $orderTestResult = orderTestResult::updateOrCreate([
                    'order_test_id' => $test->id,
                    'indicator_name' => $test_result['indicator_name'],
                    'result' => $test_result['result'],
                ],
                [
                    'material_name' => $test_result['material_name'],
                    'test_name' => $test_result['test_name'],
                    'test_code' => $test_result['test_code'],
                    'gis' => $test_result['gis'],
                    'unit_name' => $test_result['unit_name'],
                    'norm_text' => $test_result['norm_text'],
                    'ubnormal_flag' => $test_result['ubnormal_flag'],
                    'payload' => $test_result['payload'],
                    'order' => $test_result['order'],
                    'date_ready' => $test_result['date_ready'],
                    'done_employee_name' => $test_result['done_employee_name'],
                    'done_employee_id' => $test_result['done_employee_id'],
                    'done_employee_post' => $test_result['done_employee_post'],
                ]);
            }
            return $test->id;
        });

        return $this->findOrderTestById($orderTestId);
    }

    public function saveOriginalResult($test, $test_original_results)
    {
        $orderTestId = DB::transaction(function () use ($test, $test_original_results) {
            foreach ($test_original_results as $test_original_result) {
                $test->orderTestOriginalResults()->create([
                    'uri' => $test_original_result['uri']
                ]);
            }
            return $test->id;
        });

        return $this->findOrderTestById($orderTestId);
    }
}
