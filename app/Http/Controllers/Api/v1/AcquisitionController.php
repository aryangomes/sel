<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Acquisition\AcquisitionRegisterRequest;
use App\Http\Requests\Acquisition\AcquisitionUpdateRequest;
use App\Models\Acquisition;
use App\Services\AcquisitionService;
use Illuminate\Http\Response;

class AcquisitionController extends ApiController
{
    /**
     *
     * @var Acquisition
     */
    private $acquisition;

    /**
     *      
     * @var AcquisitionService
     */
    private $acquisitionService;

    public function __construct(
        AcquisitionService $acquisitionService,
        Acquisition $acquisition
    ) {

        $this->acquisitionService = $acquisitionService;
        $this->acquisition = $acquisition;
        $this->tablePermissions = 'acquisitions';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('index'),
            $this->acquisition
        );

        $this->acquisitionService->getResourceCollectionModel();

        if ($this->acquisitionService->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->acquisitionService->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->acquisitionService->exceptionFromTransaction);
            $this->setErrorResponse();
        }

        return $this->responseWithJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AcquisitionRegisterRequest $request)
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('store'),
            $this->acquisition
        );

        $requestValidated = $request->validated();

        $this->acquisitionService->create($requestValidated);

        if ($this->acquisitionService->transactionIsSuccessfully) {
            $acquisitionCreated =
                $this->acquisitionService->getResourceModel($this->acquisitionService->responseFromTransaction);

            $this->setSuccessResponse($acquisitionCreated, 'acquisition', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->acquisitionService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Acquisition  $acquisition
     * @return \Illuminate\Http\Response
     */
    public function show(Acquisition $acquisition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Acquisition  $acquisition
     * @return \Illuminate\Http\Response
     */
    public function update(AcquisitionUpdateRequest $request, Acquisition $acquisition)
    {
        $this->acquisition = $acquisition;

        $this->canPerformAction(
            $this->makeNameActionFromTable('update'),
            $this->acquisition
        );

        $requestValidated = $request->validated();

        $this->acquisitionService->update($requestValidated, $this->acquisition);

        if ($this->acquisitionService->transactionIsSuccessfully) {

            $acquisitionUpdated =
                $this->acquisitionService->getResourceModel($this->acquisition);

            $this->setSuccessResponse($acquisitionUpdated, 'acquisition', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->acquisitionService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Acquisition  $acquisition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Acquisition $acquisition)
    {

        $this->acquisition = $acquisition;

        $this->canPerformAction(
            $this->makeNameActionFromTable('delete'),
            $this->acquisition
        );

        $this->acquisitionService->delete($this->acquisition);

        if ($this->acquisitionService->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->acquisitionService->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->acquisitionService->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
