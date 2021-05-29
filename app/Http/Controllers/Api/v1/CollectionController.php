<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collection\CollectionRegisterRequest;
use App\Http\Requests\Collection\CollectionUpdateRequest;
use App\Models\Collection;
use App\Repositories\Interfaces\CollectionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectionController extends Controller
{

    private $collection;

    private $collectionRepository;

    public function __construct(
        CollectionRepositoryInterface $collectionRepository,
        Collection $collection
    ) {
        $this->collectionRepository = $collectionRepository;
        $this->collection = $collection;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->collectionRepository->getResourceCollectionModel();

        if ($this->collectionRepository->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->collectionRepository->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->collectionRepository->exceptionFromTransaction);
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
        $this->authorize('create', $this->collection);

        $requestValidated = $request->validated();

        $this->collectionRepository->create($requestValidated);

        if ($this->collectionRepository->transactionIsSuccessfully) {
            $collectionCreated =
                $this->collectionRepository->getResourceModel($this->collectionRepository->responseFromTransaction);

            $this->setSuccessResponse($collectionCreated, 'collection', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->collectionRepository->resourceName]
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
        return $this->collectionRepository->getResourceModel($collection);
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

        $this->authorize('update',  $this->collection);

        $requestValidated = $request->validated();

        $this->collectionRepository->update($requestValidated, $this->collection);

        if ($this->collectionRepository->transactionIsSuccessfully) {

            $collectionUpdated =
                $this->collectionRepository->getResourceModel($this->collection);

            $this->setSuccessResponse($collectionUpdated, 'collection', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->collectionRepository->resourceName]
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

        $this->authorize('delete',  $this->collection);

        $this->collectionRepository->delete($this->collection);

        if ($this->collectionRepository->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->collectionRepository->resourceName]
                ),
                Controller::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->collectionRepository->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
