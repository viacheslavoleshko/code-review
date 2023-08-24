<?php

namespace Modules\Indicator\Services;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Directory\Entities\CatalogIndicator;
use Modules\Indicator\DataTransferObjects\AnalysHistoryData;
use Modules\Indicator\DataTransferObjects\ApiInputAnalysHistoryData;
use Modules\Indicator\DataTransferObjects\IndicatorHistoryData;
use Modules\Indicator\DataTransferObjects\ManualInputAnalysHistoryData;
use Modules\Indicator\DataTransferObjects\OcrInputAnalysHistoryData;
use Modules\Indicator\Entities\AnalysHistory;
use Modules\Indicator\Entities\IndicatorHistory;
use Modules\Indicator\Jobs\GetProbablyAnalysisByAlias;
use Modules\Indicator\Jobs\GetProbablyIndicatorsByAlias;
use Modules\Indicator\Jobs\GetProbablyMeasureTypesByAlias;
use Modules\State\Jobs\GetProbablyPatientsStates;

class AnalysHistoryService
{
    private function getInstance()
    {
        return AnalysHistory::with([
            'laboratory',
            'analys',
            // 'probablyAnalysis',
            'indicators',
            'indicators.catalogIndicator',
            'indicators.measureType',
            'indicators.validatedWho',
            'indicators.probablyIndicators',
            'indicators.probablyMeasureTypes'
        ]);
    }

    public function list(User $user)
    {
        return $this->getInstance()
            ->where(['user_id' => $user->id])
            ->paginate(config('app.items_per_page'));
    }

    public function all(User $user)
    {
        return $this->getInstance()
            ->where(['user_id' => $user->id])
            ->get();
    }

    public function findAnalysHistoryById(int $id)
    {
        return $this->getInstance()->where(['id' => $id])->first();
    }

    public function createOcrAnalysHistory(OcrInputAnalysHistoryData $data)
    {
        $indicators = [];
        foreach ($data->indicators as $item) {
            $indicators[] = IndicatorHistoryData::from([
                'original_indicator_name' => $item->original_indicator_name,
                'original_norm_text' => $item->original_norm_text,
                'norm_flag' => $item->norm_flag ?? null,
                'result' => $item->result,
                'original_measure_type' => $item->original_measure_type
            ]);
        }

        $historyData = AnalysHistoryData::from([
            'user_id' => $data->user_id,
            'laboratory_id' => $data->laboratory_id,
            'date' => $data->date,
            'original_analys_name' => $data->original_analys_name,
            'src_type' => 'ocr',
            'indicators' => IndicatorHistoryData::collection($indicators)
        ]);

        $this->createAnalysHistory($historyData);
    }

    public function createApiAnalysHistory(ApiInputAnalysHistoryData $data)
    {
        $indicators = [];
        foreach ($data->indicators as $item) {
            $indicators[] = IndicatorHistoryData::from([
                'original_indicator_name' => $item->original_indicator_name,
                'original_norm_text' => $item->original_norm_text,
                'norm_flag' => $item->norm_flag ?? null,
                'result' => $item->result,
                'original_measure_type' => $item->original_measure_type
            ]);
        }

        $historyData = AnalysHistoryData::from([
            'user_id' => $data->user_id,
            'laboratory_id' => $data->laboratory_id,
            'date' => $data->date,
            'original_analys_name' => $data->original_analys_name,
            'src_type' => 'api',
            'indicators' => IndicatorHistoryData::collection($indicators)
        ]);

        $this->createAnalysHistory($historyData);
    }

    public function createManualAnalysHistory(ManualInputAnalysHistoryData $data)
    {
        $indicators = [];
        foreach ($data->indicators as $item) {
            $indicators[] = IndicatorHistoryData::from([
                'indicator_id' => $item->indicator_id,
                'original_norm_text' => $item->original_norm_text,
                'norm_flag' => $item->norm_flag ?? null,
                'result' => $item->result,
                'validated_at' => date("Y-m-d H:i:s"),
                'validated_who' => \Auth::id(),
                'measure_type_id' => $item->measure_type_id,
            ]);
        }

        $historyData = AnalysHistoryData::from([
            'user_id' => $data->user_id,
            'laboratory_id' => $data->laboratory_id,
            'date' => $data->date,
            'analys_id' => $data->analys_id,
            'src_type' => 'manual',
            'indicators' => IndicatorHistoryData::collection($indicators)
        ]);

        $analysHistoryCreatedId = $this->createAnalysHistory($historyData);

        return $this->findAnalysHistoryById($analysHistoryCreatedId);
    }

    public function updateManualAnalysHistory(AnalysHistory $history, ManualInputAnalysHistoryData $data)
    {
        $indicators = [];
        foreach ($data->indicators as $item) {
            $indicators[] = IndicatorHistoryData::from([
                'indicator_id' => $item->indicator_id,
                'original_norm_text' => $item->original_norm_text,
                'norm_flag' => $item->norm_flag ?? null,
                'result' => $item->result,
                'validated_at' => date("Y-m-d H:i:s"),
                'validated_who' => \Auth::id(),
                'measure_type_id' => $item->measure_type_id,
            ]);
        }

        $historyData = AnalysHistoryData::from([
            'user_id' => $data->user_id,
            'laboratory_id' => $data->laboratory_id,
            'date' => $data->date,
            'analys_id' => $data->analys_id,
            'src_type' => 'manual',
            'indicators' => IndicatorHistoryData::collection($indicators)
        ]);

        $analysHistoryUpdatedId = $this->updateAnalysHistory($history, $historyData);

        return $this->findAnalysHistoryById($analysHistoryUpdatedId);
    }

    public function deleteAnalysHistory(AnalysHistory $history)
    {
        \DB::transaction(function () use ($history) {
            $history->indicators()->delete();
            $history->delete();
        });
    }

    public function getLastPatientIndicators(User $user)
    {
        $analysis = $this->getInstance()->where(['user_id' => $user->id])->orderBy('date', 'asc')->get();
        $indicators = [];

        foreach ($analysis as $analys) {

            foreach ($analys->indicators as $indicator) {

                if (!$indicator->isConfirmed()) {
                    continue;
                }

                $indicators[$indicator->getSlug()] = [
                    'slug'  => $indicator->getSlug(),
                    'name' => $indicator->getName(),
                    'value' => $indicator->result,
                    'norm_text' => $indicator->getNormText(),
                    'norm_flag' =>  $indicator->norm_flag,
                    'measure_type' =>  $indicator->getMeasureType(),
                    'measure_type_slug' =>  $indicator->getMeasureTypeSlug()
                ];
            }
        }

        return $indicators;
    }

    public function getFormattedLastPatientIndicators(User $user)
    {
        $indicators = $this->getLastPatientIndicators($user);
        $signals = [];

        foreach ($indicators as $indicator) {
            $signals[$indicator['slug']] = ['value' => $indicator['value'], 'measure_type' => $indicator['measure_type_slug']];
        }

        return $signals;
    }

    private function updateAnalysHistory(AnalysHistory $history, AnalysHistoryData $data)
    {
        $historyId = \DB::transaction(function () use ($history, $data) {
            $history->update(
                [
                    'user_id' => $data->user_id,
                    'laboratory_id' => $data->laboratory_id,
                    'date' => $data->date,
                    'original_analys_name' => $data->original_analys_name,
                    'analys_id' => $data->analys_id,
                    'src_type' => $data->src_type
                ]
            );

            GetProbablyPatientsStates::dispatch(User::where(['id' => $data->user_id])->first())
                ->onQueue(config('services.algorithm.get_probably_patient_states_queue_name'));

            if ($data->original_analys_name !== null) {
                GetProbablyAnalysisByAlias::dispatch($data->original_analys_name, $history->id)
                    ->onQueue(config('services.alias.get_probably_analysis_by_alias_queue_name'));
            }

            $history->indicators()->delete();

            foreach ($data->indicators as $indicator) {
                $this->createIndicatorHistory($history, $indicator);
            }
            return $history->id;
        });

        return $historyId;
    }

    private function createAnalysHistory(AnalysHistoryData $data)
    {
        $analysHistoryId = \DB::transaction(function () use ($data) {
            $analysHistory = AnalysHistory::create(
                [
                    'user_id' => $data->user_id,
                    'laboratory_id' => $data->laboratory_id,
                    'date' => $data->date,
                    'original_analys_name' => $data->original_analys_name,
                    'analys_id' => $data->analys_id,
                    'src_type' => $data->src_type,
                    'probably_analysis' => $data->probably_analysis
                ]
            );
            foreach ($data->indicators as $indicator) {
                $this->createIndicatorHistory($analysHistory, $indicator);
            }

            GetProbablyPatientsStates::dispatch(User::where(['id' => $data->user_id])->first())
                ->onQueue(config('services.algorithm.get_probably_patient_states_queue_name'));

            if ($analysHistory->original_analys_name !== null) {
                GetProbablyAnalysisByAlias::dispatch($analysHistory->original_analys_name, $analysHistory->id)
                    ->onQueue(config('services.alias.get_probably_analysis_by_alias_queue_name'));
            }

            return $analysHistory->id;
        });

        return $analysHistoryId;
    }

    private function createIndicatorHistory(AnalysHistory $history, IndicatorHistoryData $data)
    {
        $indicatorHistory = IndicatorHistory::create([
            'history_id' => $history->id,
            'original_indicator_name' => $data->original_indicator_name,
            'indicator_id' => $data->indicator_id,
            'original_norm_text' => $data->original_norm_text,
            'norm_flag' => $data->norm_flag ?? null,
            'result' => $data->result,
            'validated_at' => $data->validated_at,
            'validated_who' => $data->validated_who,
            'original_measure_type' => $data->original_measure_type,
            'measure_type_id' => $data->measure_type_id,
            'probably_indicators' => $data->probably_indicators,
            'probably_measure_types' => $data->probably_measure_types
        ]);

        if ($indicatorHistory->original_indicator_name !== null) {
            GetProbablyIndicatorsByAlias::dispatch($indicatorHistory->original_indicator_name, $indicatorHistory->id)
                ->onQueue(config('services.alias.get_probably_indicators_by_alias_queue_name'));
        }

        if ($indicatorHistory->original_measure_type !== null) {
            GetProbablyMeasureTypesByAlias::dispatch($indicatorHistory->original_measure_type, $indicatorHistory->id)
                ->onQueue(config('services.alias.get_probably_measure_types_by_alias_queue_name'));
        }
    }

    public function getAllIndicators()
    {
        $indicators = CatalogIndicator::with('etalonMeasureType')->get();

        $mappedcollection = $indicators->map(function ($indicator, $key) {
            return [
                'signal' => $indicator->slug,
                'signal_name' => $indicator->name,
                'signal_measure_type' => $indicator->etalonMeasureType->name ?? '',
                'signal_norm_text' => $indicator->etalon_norm_text,
            ];
        });

        return $mappedcollection->toArray();
    }
}
