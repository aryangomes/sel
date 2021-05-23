<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectionType\CollectionTypeRegisterRequest;
use App\Http\Requests\CollectionType\CollectionTypeUpdateRequest;
use App\Models\CollectionType;
use App\Repositories\Interfaces\CollectionTypeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectionTypeController extends Controller
{

    private $collectionType;

    private $collectionTypeRepository;

    public function __construct(
        CollectionTypeRepositoryInterface $collectionTypeRepository,
        CollectionType $collectionType
    ) {
        $this->collectionTypeRepository = $collectionTypeRepository;
        $this->collectionType = $collectionType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->collectionTypeRepository->getResourceCollectionModel();

        if ($this->collectionTypeRepository->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->collectionTypeRepository->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->collectionTypeRepository->exceptionFromTransaction);
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
        $this->authorize('create', $this->collectionType);

        $requestValidated = $request->validated();

        $this->collectionTypeRepository->create($requestValidated);

        if ($this->collectionTypeRepository->transactionIsSuccessfully) {
            $collectionTypeCreated =
                $this->collectionTypeRepository->getResourceModel($this->collectionTypeRepository->responseFromTransaction);

            $this->setSuccessResponse($collectionTypeCreated, 'collectionType', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->collectionTypeRepository->resourceName]
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

        $this->authorize('update',  $this->collectionType);

        $requestValidated = $request->validated();

        $this->collectionTypeRepository->update($requestValidated, $this->collectionType);

        if ($this->collectionTypeRepository->transactionIsSuccessfully) {

            $collectionTypeUpdated =
                $this->collectionTypeRepository->getResourceModel($this->collectionType);

            $this->setSuccessResponse($collectionTypeUpdated, 'collectionType', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->collectionTypeRepository->resourceName]
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

        $this->authorize('delete',  $this->collectionType);

        $this->collectionTypeRepository->delete($this->collectionType);

        if ($this->collectionTypeRepository->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->collectionTypeRepository->resourceName]
                ),
                Controller::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->collectionTypeRepository->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
