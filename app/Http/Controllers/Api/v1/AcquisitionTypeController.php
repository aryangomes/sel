<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcquisitionType\AcquisitionTypeRegisterRequest;
use App\Http\Requests\AcquisitionType\AcquisitionTypeUpdateRequest;
use App\Models\AcquisitionType;
use App\Repositories\Interfaces\AcquisitionTypeRepositoryInterface;

use Illuminate\Http\Response;

class AcquisitionTypeController extends ApiController
{

    private $acquisitionType;

    private $acquisitionTypeRepository;

    public function __construct(
        AcquisitionTypeRepositoryInterface $acquisitionTypeRepository,
        AcquisitionType $acquisitionType
    ) {
        $this->acquisitionTypeRepository = $acquisitionTypeRepository;
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
        $this->canPerformAction($this->makeNameActionFromTable('index'), 
        $this->acquisitionType);

        $this->acquisitionTypeRepository->getResourceCollectionModel();

        if ($this->acquisitionTypeRepository->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->acquisitionTypeRepository->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->acquisitionTypeRepository->exceptionFromTransaction);
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
        $this->canPerformAction($this->makeNameActionFromTable('create'), 
        $this->acquisitionType);

        $requestValidated = $request->validated();

        $this->acquisitionTypeRepository->create($requestValidated);

        if ($this->acquisitionTypeRepository->transactionIsSuccessfully) {
            $acquisitionTypeCreated =
                $this->acquisitionTypeRepository->getResourceModel($this->acquisitionTypeRepository->responseFromTransaction);

            $this->setSuccessResponse($acquisitionTypeCreated, 'acquisitionType', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->acquisitionTypeRepository->resourceName]
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

        $this->canPerformAction($this->makeNameActionFromTable('update'), 
        $this->acquisitionType);

        $requestValidated = $request->validated();

        $this->acquisitionTypeRepository->update($requestValidated, $this->acquisitionType);

        if ($this->acquisitionTypeRepository->transactionIsSuccessfully) {

            $acquisitionTypeUpdated =
                $this->acquisitionTypeRepository->getResourceModel($this->acquisitionType);

            $this->setSuccessResponse($acquisitionTypeUpdated, 'acquisitionType', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->acquisitionTypeRepository->resourceName]
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

        $this->canPerformAction($this->makeNameActionFromTable('delete'), 
        $this->acquisitionType);

        $this->acquisitionTypeRepository->delete($this->acquisitionType);

        if ($this->acquisitionTypeRepository->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->acquisitionTypeRepository->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->acquisitionTypeRepository->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
