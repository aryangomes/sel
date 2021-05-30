<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectionCopy\CollectionCopyRegisterRequest;
use App\Http\Requests\CollectionCopy\CollectionCopyUpdateRequest;
use App\Models\CollectionCopy;
use App\Repositories\Interfaces\CollectionCopyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectionCopyController extends Controller
{

    private $collectionCopy;

    private $collectionCopyRepository;

    public function __construct(
        CollectionCopyRepositoryInterface $collectionCopyRepository,
        CollectionCopy $collectionCopy
    ) {
        $this->collectionCopyRepository = $collectionCopyRepository;
        $this->collectionCopy = $collectionCopy;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->collectionCopyRepository->getResourceCollectionModel();

        if ($this->collectionCopyRepository->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->collectionCopyRepository->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->collectionCopyRepository->exceptionFromTransaction);
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
        $this->authorize('create', $this->collectionCopy);

        $requestValidated = $request->validated();

        $this->collectionCopyRepository->create($requestValidated);

        if ($this->collectionCopyRepository->transactionIsSuccessfully) {
            $collectionCopyCreated =
                $this->collectionCopyRepository->getResourceModel($this->collectionCopyRepository->responseFromTransaction);

            $this->setSuccessResponse($collectionCopyCreated, 'collectionCopy', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->collectionCopyRepository->resourceName]
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
        return $this->collectionCopyRepository->getResourceModel($collectionCopy);
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

        $this->authorize('update',  $this->collectionCopy);

        $requestValidated = $request->validated();

        $this->collectionCopyRepository->update($requestValidated, $this->collectionCopy);

        if ($this->collectionCopyRepository->transactionIsSuccessfully) {

            $collectionCopyUpdated =
                $this->collectionCopyRepository->getResourceModel($this->collectionCopy);

            $this->setSuccessResponse($collectionCopyUpdated, 'collectionCopy', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->collectionCopyRepository->resourceName]
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

        $this->authorize('delete',  $this->collectionCopy);

        $this->collectionCopyRepository->delete($this->collectionCopy);

        if ($this->collectionCopyRepository->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->collectionCopyRepository->resourceName]
                ),
                Controller::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->collectionCopyRepository->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
