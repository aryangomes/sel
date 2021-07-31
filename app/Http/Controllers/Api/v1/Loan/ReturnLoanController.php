<?php

namespace App\Http\Controllers\Api\v1\Loan;

use App\Http\Controllers\Api\v1\ApiController;
use App\Http\Requests\Loan\LoanReturnRequest;
use App\Models\Loan\Loan;
use App\Repositories\Interfaces\LoanRepositoryInterface;
use App\Services\Loan\ReturnLoanService;
use Illuminate\Http\Response;

class ReturnLoanController extends ApiController
{
    private $loanRepository;

    public function __construct(
        LoanRepositoryInterface $loanRepository
    ) {
        $this->loanRepository = $loanRepository;
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

        $this->loanRepository->loan = $this->loan;

        $returnLoanService = new ReturnLoanService($this->loanRepository);

        $returnLoanService();
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

    private function unlockedCopiesCollection()
    {
    }

    private function setDateReturnDate()
    {
    }
}
