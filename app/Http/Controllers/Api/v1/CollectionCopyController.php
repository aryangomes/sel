<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectionCopy\CollectionCopyRegisterRequest;
use App\Http\Requests\CollectionCopy\CollectionCopyUpdateRequest;
use App\Models\CollectionCopy;
use App\Services\CollectionCopyService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectionCopyController extends ApiController
{
    /**
     *
     * @var CollectionCopy
     */
    private $collectionCopy;

    /**
     *
     * @var CollectionCopyService
     */
    private $collectionCopyService;

    public function __construct(
        CollectionCopyService $collectionCopyService,
        CollectionCopy $collectionCopy
    ) {
        $this->collectionCopyService = $collectionCopyService;
        $this->collectionCopy = $collectionCopy;
        $this->tablePermissions = 'collection_copies';
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
            $this->collectionCopy
        );

        $this->collectionCopyService->getResourceCollectionModel();

        if ($this->collectionCopyService->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->collectionCopyService->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->collectionCopyService->exceptionFromTransaction);
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
    public function store(CollectionCopyRegisterRequest $request)
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('store'),
            $this->collectionCopy
        );

        $requestValidated = $request->validated();

        $this->collectionCopyService->create($requestValidated);

        if ($this->collectionCopyService->transactionIsSuccessfully) {
            $collectionCopyCreated =
                $this->collectionCopyService->getResourceModel($this->collectionCopyService->responseFromTransaction);

            $this->setSuccessResponse($collectionCopyCreated, 'collectionCopy', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->collectionCopyService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CollectionCopy  $collectionCopy
     * @return \Illuminate\Http\Response
     */
    public function show(CollectionCopy $collectionCopy)
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('view'),
            $this->collectionCopy
        );
        return $this->collectionCopyService->getResourceModel($collectionCopy);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CollectionCopy  $collectionCopy
     * @return \Illuminate\Http\Response
     */
    public function edit(CollectionCopy $collectionCopy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CollectionCopy  $collectionCopy
     * @return \Illuminate\Http\Response
     */
    public function update(CollectionCopyUpdateRequest $request, CollectionCopy $collectionCopy)
    {
        $this->collectionCopy = $collectionCopy;

        $this->canPerformAction(
            $this->makeNameActionFromTable('update'),
            $this->collectionCopy
        );

        $requestValidated = $request->validated();

        $this->collectionCopyService->update($requestValidated, $this->collectionCopy);

        if ($this->collectionCopyService->transactionIsSuccessfully) {

            $collectionCopyUpdated =
                $this->collectionCopyService->getResourceModel($this->collectionCopy);

            $this->setSuccessResponse($collectionCopyUpdated, 'collectionCopy', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->collectionCopyService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CollectionCopy  $collectionCopy
     * @return \Illuminate\Http\Response
     */
    public function destroy(CollectionCopy $collectionCopy)
    {
        $this->collectionCopy = $collectionCopy;

        $this->canPerformAction(
            $this->makeNameActionFromTable('delete'),
            $this->collectionCopy
        );

        $this->collectionCopyService->delete($this->collectionCopy);

        if ($this->collectionCopyService->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->collectionCopyService->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->collectionCopyService->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
