<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectionType\CollectionTypeRegisterRequest;
use App\Http\Requests\CollectionType\CollectionTypeUpdateRequest;
use App\Models\CollectionType;
use App\Services\CollectionTypeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectionTypeController extends ApiController
{

    /**
     *
     * @var CollectionType
     */
    private $collectionType;

    /**
     *
     * @var CollectionTypeService
     */
    private $collectionTypeService;

    public function __construct(
        CollectionTypeService $collectionTypeService,
        CollectionType $collectionType
    ) {
        $this->collectionTypeService = $collectionTypeService;
        $this->collectionType = $collectionType;
        $this->tablePermissions = 'collection_types';
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
            $this->collectionType
        );

        $this->collectionTypeService->getResourceCollectionModel();

        if ($this->collectionTypeService->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->collectionTypeService->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->collectionTypeService->exceptionFromTransaction);
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
    public function store(CollectionTypeRegisterRequest $request)
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('store'),
            $this->collectionType
        );


        $requestValidated = $request->validated();

        $this->collectionTypeService->create($requestValidated);

        if ($this->collectionTypeService->transactionIsSuccessfully) {
            $collectionTypeCreated =
                $this->collectionTypeService->getResourceModel($this->collectionTypeService->responseFromTransaction);

            $this->setSuccessResponse($collectionTypeCreated, 'collectionType', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->collectionTypeService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CollectionType  $collectionType
     * @return \Illuminate\Http\Response
     */
    public function show(CollectionType $collectionType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CollectionType  $collectionType
     * @return \Illuminate\Http\Response
     */
    public function edit(CollectionType $collectionType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CollectionType  $collectionType
     * @return \Illuminate\Http\Response
     */
    public function update(CollectionTypeUpdateRequest $request, CollectionType $collectionType)
    {
        $this->collectionType = $collectionType;

        $this->canPerformAction(
            $this->makeNameActionFromTable('update'),
            $this->collectionType
        );


        $requestValidated = $request->validated();

        $this->collectionTypeService->update($requestValidated, $this->collectionType);

        if ($this->collectionTypeService->transactionIsSuccessfully) {

            $collectionTypeUpdated =
                $this->collectionTypeService->getResourceModel($this->collectionType);

            $this->setSuccessResponse($collectionTypeUpdated, 'collectionType', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->collectionTypeService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CollectionType  $collectionType
     * @return \Illuminate\Http\Response
     */
    public function destroy(CollectionType $collectionType)
    {
        $this->collectionType = $collectionType;

        $this->canPerformAction(
            $this->makeNameActionFromTable('delete'),
            $this->collectionType
        );


        $this->collectionTypeService->delete($this->collectionType);

        if ($this->collectionTypeService->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->collectionTypeService->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->collectionTypeService->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
