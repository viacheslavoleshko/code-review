<?php

namespace Modules\Terralab\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Directory\Entities\CatalogServicePackage;
use Modules\Terralab\Transformers\PackagesResource;
use Modules\Terralab\Services\PackageService;
use Modules\Terralab\Http\Requests\UpdatePackageAnalysesRequest;

class TerralabPackageController extends Controller
{
    public $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $locale, CatalogServicePackage $package)
    {
        return response(new PackagesResource($this->packageService->list($package)));
    }

    /**
     * update resource in storage.
     */
    public function update(UpdatePackageAnalysesRequest $request, $locale, CatalogServicePackage $package)
    {
        return response(new PackagesResource($this->packageService->update($request, $package)));
    }
}

