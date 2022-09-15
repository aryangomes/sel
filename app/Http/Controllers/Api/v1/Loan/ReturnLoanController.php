<?php

namespace App\Http\Controllers\Api\v1\Loan;

use App\Http\Controllers\Api\v1\ApiController;
use App\Http\Requests\Loan\LoanReturnRequest;
use App\Models\Loan\Loan;
use App\Services\Loan\ReturnLoanService;
use App\Services\LoanService;
use Illuminate\Http\Response;

class ReturnLoanController extends ApiController
{
    private $loanService;

    public function __construct(
        LoanService $loanService
    ) {
        $this->loanService = $loanService;
        $this->tablePermissions = 'loans';
    }

    public function __invoke(LoanReturnRequest $request)
    {
        $requestValidated = $request->validated();

        $this->loan = Loan::find($requestValidated['idLoan']);

        $this->canPerformAction(
            $this->makeNameActionFromTable('update'),
            $this->loan
        );

        $this->loanService->loan = $this->loan;

        $returnLoanService = new ReturnLoanService($this->loanService);

        $returnLoanService();
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

    private function unlockedCopiesCollection()
    {
    }

    private function setDateReturnDate()
    {
    }
}