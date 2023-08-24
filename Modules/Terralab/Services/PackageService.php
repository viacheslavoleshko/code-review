<?php

namespace Modules\Terralab\Services;

use Illuminate\Support\Facades\DB;
use Modules\Directory\Entities\CatalogServicePackage;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Terralab\Entities\PackageAnalysis;

class PackageService
{
    private function getInstance()
    {
        return CatalogServicePackage::with([
            'packageAnalyses'
        ]);
    }

    public function list(CatalogServicePackage $package)
    {
        return $this->getInstance()->where('id', $package->id)->first();
    }

    public function update(FormRequest $request, CatalogServicePackage $package)
    {
        DB::transaction(function () use ($request, $package) {
            $this->clearRelations($package);
            foreach ($request->analyses as $analysis) {
                PackageAnalysis::query()->create([
                    'analysis' => $analysis,
                    'catalog_service_package_id' => $package->id
                ]);
            }
        });
        return $this->getInstance()->where('id', $package->id)->first();
    }

    private function clearRelations(CatalogServicePackage $package)
    {
        $package->packageAnalyses()->get()->each(function ($item) {
            $item->delete();
        });
    }
}
