<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Loan\LoanRegisterRequest;
use App\Http\Requests\Loan\LoanUpdateRequest;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoanController extends ApiController
{

    /**
     *
     * @var Loan
     */
    private $loan;

    /**
     *
     * @var LoanService
     */
    private $loanService;

    public function __construct(
        LoanService $loanService,
        Loan $loan
    ) {
        $this->loanService = $loanService;
        $this->loan = $loan;
        $this->tablePermissions = 'loans';
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
            $this->loan
        );

        $this->loanService->getResourceCollectionModel();

        if ($this->loanService->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->loanService->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->loanService->exceptionFromTransaction);
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
    public function store(LoanRegisterRequest $request)
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('store'),
            $this->loan
        );

        $requestValidated = $request->validated();

        $this->loanService->create($requestValidated);

        if ($this->loanService->transactionIsSuccessfully) {
            $loanCreated =
                $this->loanService->getResourceModel($this->loanService->responseFromTransaction);

            $this->setSuccessResponse($loanCreated, 'loan', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->loanService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        $this->loan = $loan;

        $this->canPerformActionOrResourceBelongsToUser(
            $this->makeNameActionFromTable('view'),
            $this->loan->idBorrowerUser,
            $this->loan
        );


        return $this->loanService->getResourceModel($loan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(LoanUpdateRequest $request, Loan $loan)
    {
        $this->loan = $loan;

        $this->canPerformAction(
            $this->makeNameActionFromTable('update'),
            $this->loan
        );

        $requestValidated = $request->validated();

        $this->loanService->update($requestValidated, $this->loan);

        if ($this->loanService->transactionIsSuccessfully) {

            $loanUpdated =
                $this->loanService->getResourceModel($this->loan);

            $this->setSuccessResponse($loanUpdated, 'loan', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->loanService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        $this->loan = $loan;

        $this->canPerformAction(
            $this->makeNameActionFromTable('delete'),
            $this->loan
        );

        $this->loanService->delete($this->loan);

        if ($this->loanService->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->loanService->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->loanService->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
