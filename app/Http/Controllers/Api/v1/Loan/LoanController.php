<?php

namespace App\Http\Controllers\Api\v1\Loan;


use App\Http\Controllers\Api\v1\ApiController;
use App\Http\Requests\Loan\LoanRegisterRequest;
use App\Http\Requests\Loan\LoanUpdateRequest;
use App\Models\Loan\Loan;
use App\Repositories\Interfaces\LoanRepositoryInterface;
use Illuminate\Http\Response;

class LoanController extends ApiController
{

    private $loan;

    private $loanRepository;

    public function __construct(
        LoanRepositoryInterface $loanRepository,
        Loan $loan
    ) {
        $this->loanRepository = $loanRepository;
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

        $this->loanRepository->getResourceCollectionModel();

        if ($this->loanRepository->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->loanRepository->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->loanRepository->exceptionFromTransaction);
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
        $this->setSuccessResponse('HTTP_METHOD_NOT_ALLOWED', '', Response::HTTP_METHOD_NOT_ALLOWED);

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan\Loan  $loan
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


        return $this->loanRepository->getResourceModel($loan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan\Loan  $loan
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
     * @param  \App\Models\Loan\Loan  $loan
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

        $this->loanRepository->update($requestValidated, $this->loan);

        if ($this->loanRepository->transactionIsSuccessfully) {

            $loanUpdated =
                $this->loanRepository->getResourceModel($this->loan);

            $this->setSuccessResponse($loanUpdated, 'loan', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->loanRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        $this->loan = $loan;

        $this->canPerformAction(
            $this->makeNameActionFromTable('delete'),
            $this->loan
        );

        $this->loanRepository->delete($this->loan);

        if ($this->loanRepository->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->loanRepository->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->loanRepository->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
