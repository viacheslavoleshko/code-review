<?php

namespace Modules\Indicator\Services;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Modules\Indicator\Entities\AnalysGroup;
use Modules\Directory\Entities\CatalogAnalys;

class AnalysGroupService
{
    private function getInstance()
    {
        return AnalysGroup::with(['indicator']);
    }

    public function list(CatalogAnalys $catalogAnalys)
    {
        return $this->getInstance()
            ->where(['analys_id' => $catalogAnalys->id])
            ->get();
    }

    private function findAnalysGroupByAnalysId(int $id)
    {
        return $this->getInstance()->where(['analys_id' => $id])->get();
    }

    public function create(FormRequest $request, CatalogAnalys $catalogAnalys)
    {
        $analysGroupId = DB::transaction(function () use ($request, $catalogAnalys) {

            foreach ($request->indicators as $indicator) {
                $analysGroup = AnalysGroup::create([
                    'indicator_id' => $indicator['indicator_id'],
                    'analys_id' => $catalogAnalys->id
                ]);
            }

            return $analysGroup->id;
        });
        return $this->findAnalysGroupByAnalysId($catalogAnalys->id);
    }

    private function clearAnalysGroup(CatalogAnalys $catalogAnalys)
    {
        AnalysGroup::where(['analys_id' => $catalogAnalys->id])->delete();
    }

    public function update(FormRequest $request, CatalogAnalys $catalogAnalys)
    {
        $analysGroupId = DB::transaction(function () use ($request, $catalogAnalys) {

            $this->clearAnalysGroup($catalogAnalys);

            foreach ($request->indicators as $indicator) {
                $analysGroup = AnalysGroup::create([
                    'indicator_id' => $indicator['indicator_id'],
                    'analys_id' => $catalogAnalys->id
                ]);
            }

            return $analysGroup->id;
        });
        return $this->findAnalysGroupByAnalysId($catalogAnalys->id);
    }

    public function delete(CatalogAnalys $catalogAnalys)
    {
        DB::transaction(function () use ($catalogAnalys) {
            $this->clearAnalysGroup($catalogAnalys);
        });
    }
}
