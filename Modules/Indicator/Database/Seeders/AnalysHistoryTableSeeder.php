<?php

namespace Modules\Indicator\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;
use Modules\Indicator\DataTransferObjects\ApiInputAnalysHistoryData;
use Modules\Indicator\DataTransferObjects\ApiInputIndicatorHistoryData;
use Modules\Indicator\DataTransferObjects\ManualInputAnalysHistoryData;
use Modules\Indicator\DataTransferObjects\ManualInputIndicatorHistoryData;
use Modules\Indicator\DataTransferObjects\OcrInputAnalysHistoryData;
use Modules\Indicator\DataTransferObjects\OcrInputIndicatorHistoryData;
use Modules\Indicator\Entities\AnalysGroup;
use Modules\Directory\Entities\CatalogAnalys;
use Modules\Indicator\Services\AnalysHistoryService;

class AnalysHistoryTableSeeder extends Seeder
{
    protected $analysHistoryService;

    public function __construct(AnalysHistoryService $analysHistoryService)
    {
        $this->analysHistoryService = $analysHistoryService;
    }

    public function run()
    {
        Model::unguard();

        $patients = User::where(['role_id' => Role::PATIENT_ROLE_ID])->get();
        $catalogAnalys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'General comprehensive analysis of venous blood with ESR and leukocyte blood formula'")->first();
        $analysGroupItems = AnalysGroup::where(['analys_id' => $catalogAnalys->id])->get();

        \DB::transaction(function () use ($patients, $catalogAnalys, $analysGroupItems) {
            foreach ($patients as $patient) {
                //$this->createManualAnalysHistory($patient->id, $catalogAnalys->id, $analysGroupItems);
                //$this->createApiAnalysHistory($patient->id, $catalogAnalys->name, $analysGroupItems);
                $this->createOcrAnalysHistory($patient->id, $catalogAnalys->name, $analysGroupItems);
            }
        });
    }

    private function createOcrAnalysHistory(int $patientId, string $analysName, $analysGroupItems)
    {
        $indicators = [];
        foreach ($analysGroupItems as $item) {
            $indicators[] = new OcrInputIndicatorHistoryData($item->indicator->name, '1-100',rand(0,1), rand(1, 50), 'mg');
        }

        $historyData =  new OcrInputAnalysHistoryData(
            $patientId,
            rand(1, 2),
            date("Y-m-d H:i:s"),
            $analysName,
            OcrInputIndicatorHistoryData::collection($indicators)
        );      

        $this->analysHistoryService->createOcrAnalysHistory($historyData);
    }


    private function createApiAnalysHistory(int $patientId, string $analysName, $analysGroupItems)
    {
        $indicators = [];
        foreach ($analysGroupItems as $item) {
            $indicators[] = new ApiInputIndicatorHistoryData($item->indicator->name, '1-100',rand(0,1), rand(1, 50), 'mg');
        }

        $historyData=  new ApiInputAnalysHistoryData(
            $patientId,
            rand(1, 2),
            date("Y-m-d H:i:s"),
            $analysName,
            ApiInputIndicatorHistoryData::collection($indicators)
        );      

        $this->analysHistoryService->createApiAnalysHistory($historyData);
    }

    private function createManualAnalysHistory(int $patientId, int $analysId, $analysGroupItems)
    {
        $indicators = [];
        foreach ($analysGroupItems as $item) {
            $indicators[] = new ManualInputIndicatorHistoryData($item->indicator_id, '1-100',rand(0,1), rand(1, 50), null);
        }

        $historyData =  new ManualInputAnalysHistoryData(
            $patientId,
            rand(1, 2),
            date("Y-m-d H:i:s"),
            $analysId,
            ManualInputIndicatorHistoryData::collection($indicators)
        );      

        $this->analysHistoryService->createManualAnalysHistory($historyData);
    }    
}
