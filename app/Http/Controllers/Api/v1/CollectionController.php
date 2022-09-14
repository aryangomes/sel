<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collection\CollectionRegisterRequest;
use App\Http\Requests\Collection\CollectionUpdateRequest;
use App\Models\Collection;
use App\Repositories\Interfaces\CollectionService;
use App\Services\CollectionService as ServicesCollectionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectionController extends ApiController
{

    /**
     *
     * @var Collection
     */
    private $collection;

    /**
     *
     * @var ServicesCollectionService
     */
    private $collectionService;

    public function __construct(
        ServicesCollectionService $collectionService,
        Collection $collection
    ) {
        $this->collectionService = $collectionService;
        $this->collection = $collection;
        $this->tablePermissions = 'collections';
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
            $this->collection
        );

        $this->collectionService->getResourceCollectionModel();

        if ($this->collectionService->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->collectionService->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->collectionService->exceptionFromTransaction);
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
    public function store(CollectionRegisterRequest $request)
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('store'),
            $this->collection
        );

        $requestValidated = $request->validated();

        $this->collectionService->create($requestValidated);

        if ($this->collectionService->transactionIsSuccessfully) {
            $collectionCreated =
                $this->collectionService->getResourceModel($this->collectionService->responseFromTransaction);

            $this->setSuccessResponse($collectionCreated, 'collection', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->collectionService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function show(Collection $collection)
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('view'),
            $this->collection
        );

        return $this->collectionService->getResourceModel($collection);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function edit(Collection $collection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function update(CollectionUpdateRequest $request, Collection $collection)
    {
        $this->collection = $collection;

        $this->canPerformAction(
            $this->makeNameActionFromTable('update'),
            $this->collection
        );

        $requestValidated = $request->validated();

        $this->collectionService->update($requestValidated, $this->collection);

        if ($this->collectionService->transactionIsSuccessfully) {

            $collectionUpdated =
                $this->collectionService->getResourceModel($this->collection);

            $this->setSuccessResponse($collectionUpdated, 'collection', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->collectionService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collection $collection)
    {
        $this->collection = $collection;

        $this->canPerformAction(
            $this->makeNameActionFromTable('delete'),
            $this->collection
        );

        $this->collectionService->delete($this->collection);

        if ($this->collectionService->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->collectionService->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->collectionService->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
