<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\AcquisitionType\AcquisitionTypeRegisterRequest;
use App\Http\Requests\AcquisitionType\AcquisitionTypeUpdateRequest;
use App\Models\AcquisitionType;
use App\Services\AcquisitionTypeService;
use Illuminate\Http\Response;

class AcquisitionTypeController extends ApiController
{
    /**
     *
     * @var AcquisitionType
     */
    private $acquisitionType;

    /**
     *
     * @var AcquisitionTypeService
     */
    private $acquisitionTypeService;

    public function __construct(
        AcquisitionTypeService $acquisitionTypeService,
        AcquisitionType $acquisitionType
    ) {
        $this->acquisitionTypeService = $acquisitionTypeService;
        $this->acquisitionType = $acquisitionType;
        $this->tablePermissions = 'acquisition_types';
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
            $this->acquisitionType
        );

        $this->acquisitionTypeService->getResourceCollectionModel();

        if ($this->acquisitionTypeService->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->acquisitionTypeService->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->acquisitionTypeService->exceptionFromTransaction);
            $this->setErrorResponse();
        }

        return $this->responseWithJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AcquisitionTypeRegisterRequest $request)
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('create'),
            $this->acquisitionType
        );

        $requestValidated = $request->validated();

        $this->acquisitionTypeService->create($requestValidated);

        if ($this->acquisitionTypeService->transactionIsSuccessfully) {
            $acquisitionTypeCreated =
                $this->acquisitionTypeService->getResourceModel($this->acquisitionTypeService->responseFromTransaction);

            $this->setSuccessResponse($acquisitionTypeCreated, 'acquisitionType', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->acquisitionTypeService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AcquisitionType  $acquisitionType
     * @return \Illuminate\Http\Response
     */
    public function show(AcquisitionType $acquisitionType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AcquisitionType  $acquisitionType
     * @return \Illuminate\Http\Response
     */
    public function edit(AcquisitionType $acquisitionType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AcquisitionType  $acquisitionType
     * @return \Illuminate\Http\Response
     */
    public function update(AcquisitionTypeUpdateRequest $request, AcquisitionType $acquisitionType)
    {
        $this->acquisitionType = $acquisitionType;

        $this->canPerformAction(
            $this->makeNameActionFromTable('update'),
            $this->acquisitionType
        );

        $requestValidated = $request->validated();

        $this->acquisitionTypeService->update($requestValidated, $this->acquisitionType);

        if ($this->acquisitionTypeService->transactionIsSuccessfully) {

            $acquisitionTypeUpdated =
                $this->acquisitionTypeService->getResourceModel($this->acquisitionType);

            $this->setSuccessResponse($acquisitionTypeUpdated, 'acquisitionType', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->acquisitionTypeService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AcquisitionType  $acquisitionType
     * @return \Illuminate\Http\Response
     */
    public function destroy(AcquisitionType $acquisitionType)
    {
        $this->acquisitionType = $acquisitionType;

        $this->canPerformAction(
            $this->makeNameActionFromTable('delete'),
            $this->acquisitionType
        );

        $this->acquisitionTypeService->delete($acquisitionType);

        if ($this->acquisitionTypeService->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->acquisitionTypeService->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->acquisitionTypeService->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
