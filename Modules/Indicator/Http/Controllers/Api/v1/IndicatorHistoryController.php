<?php

namespace Modules\Indicator\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Indicator\Entities\IndicatorHistory;
use Modules\Indicator\Http\Requests\ValidateIndicatorHistoryRequest;
use Modules\Indicator\Services\IndicatorHistoryService;

class IndicatorHistoryController extends Controller
{
    protected $indicatorHistoryService;

    public function __construct(IndicatorHistoryService $indicatorHistoryService)
    {
        $this->indicatorHistoryService = $indicatorHistoryService;
    }

    public function validateIndicatorHistory(ValidateIndicatorHistoryRequest $request, $locale, IndicatorHistory $indicatorHistory)
    {
        $this->indicatorHistoryService->validate($request, $indicatorHistory);
        return response(null, Response::HTTP_ACCEPTED);
    }
}
