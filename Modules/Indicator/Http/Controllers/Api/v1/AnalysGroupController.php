<?php

namespace Modules\Indicator\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Indicator\Entities\AnalysGroup;
use Modules\Directory\Entities\CatalogAnalys;
use Modules\Indicator\Http\Requests\CreateAnalysGroupRequest;
use Modules\Indicator\Http\Requests\UpdateAnalysGroupRequest;
use Modules\Indicator\Services\AnalysGroupService;
use Modules\Indicator\Transformers\AnalysGroupResource;
use Modules\Indicator\Transformers\CatalogResource;

class AnalysGroupController extends Controller
{
    protected $analysGroupService;

    public function __construct(AnalysGroupService $analysGroupService)
    {
        $this->analysGroupService = $analysGroupService;
    }

    public function index(Request $request, $locale, CatalogAnalys $catalogAnalys)
    {

        return AnalysGroupResource::collection($this->analysGroupService->list($catalogAnalys));
    }

    public function store(CreateAnalysGroupRequest $request, $locale, CatalogAnalys $catalogAnalys)
    {
        return AnalysGroupResource::collection($this->analysGroupService->create($request, $catalogAnalys));
    }


    public function update(UpdateAnalysGroupRequest $request, $locale, CatalogAnalys $catalogAnalys)
    {
        return AnalysGroupResource::collection($this->analysGroupService->update($request, $catalogAnalys));
    }

    public function destroy(Request $request, $locale, CatalogAnalys $catalogAnalys)
    {
        $this->analysGroupService->delete($catalogAnalys);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
