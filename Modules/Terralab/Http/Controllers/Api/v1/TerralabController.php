<?php

namespace Modules\Terralab\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Modules\Terralab\Services\TerralabService;
use Modules\Directory\Entities\CatalogLaboratory;
use Modules\Terralab\Entities\PatientLaboratoryOrder;
use Modules\Terralab\Transformers\PatientLaboratoryOrderResource;
use Modules\Terralab\Http\Requests\SetStatusLaboratoryOrderRequest;
use Modules\Terralab\Http\Requests\SetResultsLaboratoryOrderRequest;
use Modules\Terralab\Http\Requests\CreatePatientLaboratoryOrderRequest;
use Modules\Terralab\Http\Requests\UpdatePatientLaboratoryOrderRequest;

class TerralabController extends Controller
{
    public $terralabService;

    public function __construct(TerralabService $terralabService)
    {
        $this->terralabService = $terralabService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, $locale, User $user)
    {
        if (!$user->isPatient()) {
            return response([
                'error' => __('This user is not a patient')
            ], Response::HTTP_UNAUTHORIZED);
        }

        $patientLaboratoryOrderHistory = $this->terralabService->list($user);
        return PatientLaboratoryOrderResource::collection($patientLaboratoryOrderHistory);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreatePatientLaboratoryOrderRequest $request, $locale, User $doctor, User $patient, CatalogLaboratory $laboratory)
    {
        if (!$doctor->isDoctor()) {
            return response([
                'error' => __('The specified doctor is not a doctor')
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$patient->isPatient()) {
            return response([
                'error' => __('The specified patient is not a patient')
            ], Response::HTTP_UNAUTHORIZED);
        }

        $patientLaboratoryOrderCreated = $this->terralabService->create($request, $doctor, $patient, $laboratory);
        $refferalDataCreated = $this->terralabService->createRefferalData($request, $patientLaboratoryOrderCreated, $doctor, $patient, $laboratory);

        $response = Http::post(config('services.diagen.diagen_base_url') . config('services.diagen.create_referral_endpoint'), $refferalDataCreated);

        if($response->getStatusCode() == 200 && isset($response['status']) && $response['status'] == 'OK') {
            $patientLaboratoryOrderCreated->update([
                'order_number' => $response['data']['doc_num'],
                'order_status' => 'STAGED'
            ]);

            return response(new PatientLaboratoryOrderResource($patientLaboratoryOrderCreated), Response::HTTP_CREATED);
        } else {
            $patientLaboratoryOrderCreated->delete();
            return $response->json();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Request $request, $locale, PatientLaboratoryOrder $patientLaboratoryOrder)
    {
        $order = $this->terralabService->findPatientLaboratoryOrderById($patientLaboratoryOrder->id);
        return new PatientLaboratoryOrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdatePatientLaboratoryOrderRequest $request, $locale, PatientLaboratoryOrder $patientLaboratoryOrder)
    {
        $this->terralabService->createOrderTests($patientLaboratoryOrder, $request->indicators);
        $refferalDataUpdated = $this->terralabService->updateRefferalData($request, $patientLaboratoryOrder);

        $response = Http::post(config('services.diagen.diagen_base_url') . config('services.diagen.update_items_endpoint'), $refferalDataUpdated);

        if($response['status'] == 'OK') {
            return response(new PatientLaboratoryOrderResource($patientLaboratoryOrder), Response::HTTP_ACCEPTED);
        } else {
            return $response->json();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(UpdatePatientLaboratoryOrderRequest $request, $locale, PatientLaboratoryOrder $patientLaboratoryOrder)
    {
        $refuseRefferalData = $this->terralabService->refuseRefferalData($request, $patientLaboratoryOrder);

        $response = Http::post(config('services.diagen.diagen_base_url') . config('services.diagen.refuse_items_endpoint'), $refuseRefferalData);

        if($response['status'] == 'OK') {
            return response(new PatientLaboratoryOrderResource($patientLaboratoryOrder), Response::HTTP_ACCEPTED);
        } else {
            return $response->json();
        }
    }

    public function setStatus(SetStatusLaboratoryOrderRequest $request, $locale)
    {
        $this->terralabService->setNewStatus($request);
        return response(null, Response::HTTP_ACCEPTED);
    }

    public function setResults(SetResultsLaboratoryOrderRequest $request, $locale)
    {
        if(empty($request->referral_data['test']['ext_id']) && empty($request->referral_data['test']['code'])) {
            return response([
                'error' => __('referral_data.test.ext_id and referral_data.test.code is null')
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->terralabService->setResults($request);

        return response(null, Response::HTTP_ACCEPTED);
    }

    public function setOriginalResults(SetResultsLaboratoryOrderRequest $request, $locale)
    {
        if(empty($request->referral_data['test']['ext_id']) && empty($request->referral_data['test']['code'])) {
            return response([
                'error' => __('referral_data.test.ext_id and referral_data.test.code is null')
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->terralabService->setResults($request, true);
        return response(null, Response::HTTP_ACCEPTED);
    }

    public function priceList(Request $request, $locale, $code = null)
    {
        if($code == null) {
            $response = cache()->remember('price-list', 86400, function () {
                return Http::acceptJson()->post(config('services.diagen.diagen_base_url') . config('services.diagen.get_price_list_endpoint'), [
                    "query" => [
                        "org_id" => config('services.diagen.org_id'),
                        "filter" => null
                    ]
                ])->json();
            });
        } else {
            $response = Http::acceptJson()->post(config('services.diagen.diagen_base_url') . config('services.diagen.get_price_list_endpoint'), [
                "query" => [
                    "org_id" => config('services.diagen.org_id'),
                    "filter" => $code
                ]
            ])->json();
        }

        return $response;
    }

    public function getStaticData(Request $request, $locale)
    {
        return new JsonResponse($this->terralabService->getStaticData());
    }
}

