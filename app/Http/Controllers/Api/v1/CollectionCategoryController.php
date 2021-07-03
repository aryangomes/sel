<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectionCategory\CollectionCategoryRegisterRequest;
use App\Http\Requests\CollectionCategory\CollectionCategoryUpdateRequest;
use App\Models\CollectionCategory;
use App\Repositories\Interfaces\CollectionCategoryRepositoryInterface;
use Illuminate\Http\Response;

class CollectionCategoryController extends ApiController
{

    private $collectionCategory;

    private $collectionCategoryRepository;

    public function __construct(
        CollectionCategoryRepositoryInterface $collectionCategoryRepository,
        CollectionCategory $collectionCategory
    ) {
        $this->collectionCategoryRepository = $collectionCategoryRepository;
        $this->collectionCategory = $collectionCategory;
        $this->tablePermissions = 'collection_categories';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->canPerformAction($this->makeNameActionFromTable('index'), 
       $this->collectionCategory);

        $this->collectionCategoryRepository->getResourceCollectionModel();

        if ($this->collectionCategoryRepository->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->collectionCategoryRepository->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->collectionCategoryRepository->exceptionFromTransaction);
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
    public function store(CollectionCategoryRegisterRequest $request)
    {
      $this->canPerformAction($this->makeNameActionFromTable('store'), 
       $this->collectionCategory);
        $requestValidated = $request->validated();

        $this->collectionCategoryRepository->create($requestValidated);

        if ($this->collectionCategoryRepository->transactionIsSuccessfully) {
            $collectionCategoryCreated =
                $this->collectionCategoryRepository->getResourceModel($this->collectionCategoryRepository->responseFromTransaction);

            $this->setSuccessResponse($collectionCategoryCreated, 'collectionCategory', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->collectionCategoryRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CollectionCategory  $collectionCategory
     * @return \Illuminate\Http\Response
     */
    public function show(CollectionCategory $collectionCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CollectionCategory  $collectionCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(CollectionCategory $collectionCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CollectionCategory  $collectionCategory
     * @return \Illuminate\Http\Response
     */
    public function update(CollectionCategoryUpdateRequest $request, CollectionCategory $collectionCategory)
    {
        $this->collectionCategory = $collectionCategory;

        $this->canPerformAction($this->makeNameActionFromTable('update'), 
       $this->collectionCategory);
        $requestValidated = $request->validated();

        $this->collectionCategoryRepository->update($requestValidated, $this->collectionCategory);

        if ($this->collectionCategoryRepository->transactionIsSuccessfully) {

            $collectionCategoryUpdated =
                $this->collectionCategoryRepository->getResourceModel($this->collectionCategory);

            $this->setSuccessResponse($collectionCategoryUpdated, 'collectionCategory', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->collectionCategoryRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CollectionCategory  $collectionCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(CollectionCategory $collectionCategory)
    {
        $this->collectionCategory = $collectionCategory;

        $this->canPerformAction($this->makeNameActionFromTable('delete'), 
       $this->collectionCategory);
        $this->collectionCategoryRepository->delete($this->collectionCategory);

        if ($this->collectionCategoryRepository->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->collectionCategoryRepository->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->collectionCategoryRepository->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
