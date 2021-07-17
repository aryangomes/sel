<?php

namespace App\Http\Controllers\Api\v1\Loan;

use App\Http\Controllers\Api\v1\ApiController;
use App\Http\Requests\Loan\LoanRegisterRequest;
use App\Models\Loan;
use App\Repositories\Interfaces\LoanRepositoryInterface;
use App\Repositories\LoanRepository;
use App\Services\Loan\RegisterLoanService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegisterController extends ApiController
{

    public function __construct(
        LoanRepositoryInterface $loanRepository,
        Loan $loan
    ) {
        $this->loanRepository = $loanRepository;
        $this->loan = $loan;
        $this->tablePermissions = 'loans';
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoanRegisterRequest $request)
    {

        $this->canPerformAction(
            $this->makeNameActionFromTable('store'),
            $this->loan
        );

        $requestValidated = $request->validated();

        $registerLoanService = new RegisterLoanService($this->loanRepository);

        $registerLoanService($requestValidated);

        if ($this->loanRepository->transactionIsSuccessfully) {
            $loanCreated =
                $this->loanRepository->getResourceModel($this->loanRepository->responseFromTransaction);

            $this->setSuccessResponse($loanCreated, 'loan', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->loanRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        return $this->responseWithJson();
    }
}
