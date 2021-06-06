<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\LoanContainsCollectionCopy\LoanContainsCollectionCopyRegisterRequest;
use App\Http\Requests\LoanContainsCollectionCopy\LoanContainsCollectionCopyUpdateRequest;
use App\Models\LoanContainsCollectionCopy;
use App\Repositories\Interfaces\LoanContainsCollectionCopyRepositoryInterface;
use Illuminate\Http\Response;

class LoanContainsCollectionCopyController extends ApiController
{

    private $loanContainsCollectionCopy;

    private $loanContainsCollectionCopyRepository;

    public function __construct(
        LoanContainsCollectionCopyRepositoryInterface $loanContainsCollectionCopyRepository,
        LoanContainsCollectionCopy $loanContainsCollectionCopy
    ) {
        $this->authorizeResource(LoanContainsCollectionCopy::class, 'loanContainsCollectionCopy');
        $this->loanContainsCollectionCopyRepository = $loanContainsCollectionCopyRepository;
        $this->loanContainsCollectionCopy = $loanContainsCollectionCopy;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->loanContainsCollectionCopyRepository->getResourceCollectionModel();

        if ($this->loanContainsCollectionCopyRepository->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->loanContainsCollectionCopyRepository->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->loanContainsCollectionCopyRepository->exceptionFromTransaction);
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
    public function store(LoanContainsCollectionCopyRegisterRequest $request)
    {

        $requestValidated = $request->validated();

        $this->loanContainsCollectionCopyRepository->create($requestValidated);

        if ($this->loanContainsCollectionCopyRepository->transactionIsSuccessfully) {
            $loanContainsCollectionCopyCreated =
                $this->loanContainsCollectionCopyRepository->getResourceModel($this->loanContainsCollectionCopyRepository->responseFromTransaction);

            $this->setSuccessResponse($loanContainsCollectionCopyCreated, 'loanContainsCollectionCopy', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->loanContainsCollectionCopyRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LoanContainsCollectionCopy  $loanContainsCollectionCopy
     * @return \Illuminate\Http\Response
     */
    public function show(LoanContainsCollectionCopy $loanContainsCollectionCopy)
    {
        return $this->loanContainsCollectionCopyRepository->getResourceModel($loanContainsCollectionCopy);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoanContainsCollectionCopy  $loanContainsCollectionCopy
     * @return \Illuminate\Http\Response
     */
    public function edit(LoanContainsCollectionCopy $loanContainsCollectionCopy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LoanContainsCollectionCopy  $loanContainsCollectionCopy
     * @return \Illuminate\Http\Response
     */
    public function update(LoanContainsCollectionCopyUpdateRequest $request, LoanContainsCollectionCopy $loanContainsCollectionCopy)
    {
        $this->loanContainsCollectionCopy = $loanContainsCollectionCopy;


        $requestValidated = $request->validated();

        $this->loanContainsCollectionCopyRepository->update($requestValidated, $this->loanContainsCollectionCopy);

        if ($this->loanContainsCollectionCopyRepository->transactionIsSuccessfully) {

            $loanContainsCollectionCopyUpdated =
                $this->loanContainsCollectionCopyRepository->getResourceModel($this->loanContainsCollectionCopy);

            $this->setSuccessResponse($loanContainsCollectionCopyUpdated, 'loanContainsCollectionCopy', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->loanContainsCollectionCopyRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoanContainsCollectionCopy  $loanContainsCollectionCopy
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanContainsCollectionCopy $loanContainsCollectionCopy)
    {
        $this->loanContainsCollectionCopy = $loanContainsCollectionCopy;


        $this->loanContainsCollectionCopyRepository->delete($this->loanContainsCollectionCopy);

        if ($this->loanContainsCollectionCopyRepository->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->loanContainsCollectionCopyRepository->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->loanContainsCollectionCopyRepository->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
