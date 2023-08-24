<?php

namespace Modules\Indicator\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Indicator\DataTransferObjects\ManualInputAnalysHistoryData;
use Modules\Indicator\DataTransferObjects\ManualInputIndicatorHistoryData;
use Modules\Indicator\Entities\AnalysHistory;
use Modules\Indicator\Http\Requests\CreateAnalysHistoryRequest;
use Modules\Indicator\Http\Requests\UpdateAnalysHistoryRequest;
use Modules\Indicator\Services\AnalysHistoryService;
use Modules\Indicator\Transformers\AnalysHistoryResource;
use Modules\Indicator\Transformers\CatalogResource;

class AnalysHistoryController extends Controller
{
    protected $analysHistoryService;

    public function __construct(AnalysHistoryService $analysHistoryService)
    {
        $this->analysHistoryService = $analysHistoryService;
    }

    public function all(Request $request, $locale, User $user)
    {
        return response(AnalysHistoryResource::collection($this->analysHistoryService->all($user)));
    }

    public function index(Request $request, $locale, User $user)
    {
        return AnalysHistoryResource::collection($this->analysHistoryService->list($user));
    }

    public function listForPatient(Request $request, $locale)
    {
        return $this->index($request, $locale, Auth::user());
    }

    public function store(CreateAnalysHistoryRequest $request, $locale, User $user)
    {
        if (!$user->isPatient()) {
            return response([
                'error' => __('This user is not a patient')
            ], Response::HTTP_UNAUTHORIZED);
        }

        $historyData = $this->makeInputData($user, $request);
        $analysHistoryCreated = $this->analysHistoryService->createManualAnalysHistory($historyData);
        return response(new AnalysHistoryResource($analysHistoryCreated), Response::HTTP_CREATED);
    }

    public function update(UpdateAnalysHistoryRequest $request, $locale, User $user, AnalysHistory $analysHistory)
    {
        if (!$user->isPatient()) {
            return response([
                'error' => __('This user is not a patient')
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($user->id !== $analysHistory->user_id) {
            return response([
                'error' => __('This analys history is not belong to this patient')
            ], Response::HTTP_UNAUTHORIZED);
        }

        $historyData = $this->makeInputData($user, $request);
        $analysHistoryUpdated = $this->analysHistoryService->updateManualAnalysHistory($analysHistory, $historyData);
        return response(new AnalysHistoryResource($analysHistoryUpdated), Response::HTTP_ACCEPTED);
    }

    public function destroy(Request $request, $locale, User $user, AnalysHistory $analysHistory)
    {
        if (!$user->isPatient()) {
            return response([
                'error' => __('This user is not a patient')
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($user->id !== $analysHistory->user_id) {
            return response([
                'error' => __('This analys history is not belong to this patient')
            ], Response::HTTP_UNAUTHORIZED);
        }

        $this->analysHistoryService->deleteAnalysHistory($analysHistory);
        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function makeInputData(User $user, FormRequest $request)
    {
        $indicators = [];
        foreach ($request->indicators as $item) {
            $indicators[] = ManualInputIndicatorHistoryData::from($item);
        }

        $historyData = new ManualInputAnalysHistoryData(
            $user->id,
            $request->laboratory_id,
            $request->date,
            $request->analys_id,
            ManualInputIndicatorHistoryData::collection($indicators)
        );

        return $historyData;
    }
}
