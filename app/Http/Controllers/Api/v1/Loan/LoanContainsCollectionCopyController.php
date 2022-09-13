<?php

namespace App\Http\Controllers\Api\v1\Loan;

use App\Http\Controllers\Api\v1\ApiController;
use App\Http\Requests\LoanContainsCollectionCopy\LoanContainsCollectionCopyRegisterRequest;
use App\Http\Requests\LoanContainsCollectionCopy\LoanContainsCollectionCopyUpdateRequest;
use App\Models\Loan\LoanContainsCollectionCopy;
use App\Services\LoanContainsCollectionCopyService;
use Illuminate\Http\Response;

class LoanContainsCollectionCopyController extends ApiController
{

    /**
     *
     * @var LoanContainsCollectionCopy
     */
    private $loanContainsCollectionCopy;

    /**
     *
     * @var LoanContainsCollectionCopyService
     */
    private $loanContainsCollectionCopyService;

    public function __construct(
        LoanContainsCollectionCopyService $loanContainsCollectionCopyService,
        LoanContainsCollectionCopy $loanContainsCollectionCopy
    ) {
        $this->authorizeResource(LoanContainsCollectionCopy::class, 'loanContainsCollectionCopy');
        $this->loanContainsCollectionCopyService = $loanContainsCollectionCopyService;
        $this->loanContainsCollectionCopy = $loanContainsCollectionCopy;
        $this->tablePermissions = 'loan_contains_collection_copies';
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
            $this->loanContainsCollectionCopy
        );

        $this->loanContainsCollectionCopyService->getResourceCollectionModel();

        if ($this->loanContainsCollectionCopyService->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->loanContainsCollectionCopyService->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->loanContainsCollectionCopyService->exceptionFromTransaction);
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

        $this->canPerformAction(
            $this->makeNameActionFromTable('store'),
            $this->loanContainsCollectionCopy
        );

        $requestValidated = $request->validated();

        $this->loanContainsCollectionCopyService->create($requestValidated);

        if ($this->loanContainsCollectionCopyService->transactionIsSuccessfully) {
            $loanContainsCollectionCopyCreated =
                $this->loanContainsCollectionCopyService->getResourceModel($this->loanContainsCollectionCopyService->responseFromTransaction);

            $this->setSuccessResponse($loanContainsCollectionCopyCreated, 'loanContainsCollectionCopy', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->loanContainsCollectionCopyService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan\LoanContainsCollectionCopy  $loanContainsCollectionCopy
     * @return \Illuminate\Http\Response
     */
    public function show(LoanContainsCollectionCopy $loanContainsCollectionCopy)
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('view'),
            $this->loanContainsCollectionCopy
        );

        return $this->loanContainsCollectionCopyService->getResourceModel($loanContainsCollectionCopy);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan\LoanContainsCollectionCopy  $loanContainsCollectionCopy
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
     * @param  \App\Models\Loan\LoanContainsCollectionCopy  $loanContainsCollectionCopy
     * @return \Illuminate\Http\Response
     */
    public function update(LoanContainsCollectionCopyUpdateRequest $request, LoanContainsCollectionCopy $loanContainsCollectionCopy)
    {
        $this->loanContainsCollectionCopy = $loanContainsCollectionCopy;

        $this->canPerformAction(
            $this->makeNameActionFromTable('update'),
            $this->loanContainsCollectionCopy
        );

        $requestValidated = $request->validated();

        $this->loanContainsCollectionCopyService->update($requestValidated, $this->loanContainsCollectionCopy);

        if ($this->loanContainsCollectionCopyService->transactionIsSuccessfully) {

            $loanContainsCollectionCopyUpdated =
                $this->loanContainsCollectionCopyService->getResourceModel($this->loanContainsCollectionCopy);

            $this->setSuccessResponse($loanContainsCollectionCopyUpdated, 'loanContainsCollectionCopy', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->loanContainsCollectionCopyService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan\LoanContainsCollectionCopy  $loanContainsCollectionCopy
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanContainsCollectionCopy $loanContainsCollectionCopy)
    {
        $this->loanContainsCollectionCopy = $loanContainsCollectionCopy;

        $this->canPerformAction(
            $this->makeNameActionFromTable('delete'),
            $this->loanContainsCollectionCopy
        );

        $this->loanContainsCollectionCopyService->delete($this->loanContainsCollectionCopy);

        if ($this->loanContainsCollectionCopyService->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->loanContainsCollectionCopyService->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->loanContainsCollectionCopyService->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
