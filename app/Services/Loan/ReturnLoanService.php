<?php

namespace App\Services\Loan;

use App\Actions\Loan\GenerateLoanIdentifierAction;
use App\Models\CollectionCopy;
use App\Models\User;
use App\Repositories\LoanRepository;
use App\Services\ServiceInterface;
use Carbon\Carbon;

class ReturnLoanService implements ServiceInterface
{

    private $loanRepository;

    public function __construct(

        LoanRepository $loanRepository
    ) {

        $this->loanRepository = $loanRepository;
    }

    public function __invoke()
    {

        $this->execute();
    }


    public function execute()
    {

        $actionWasExecuted = false;

        try {

            $dataToUpdate = [
                'returnDate' => Carbon::now(),
                'status' => 'returned',
            ];

            $this->loanRepository->update($dataToUpdate, $this->loanRepository->loan);

            // $this->loanRepository->loan->setStatusLoanToReturned();

            // $this->unlockCollectionCopy();

            if ($this->loanRepository->transactionIsSuccessfully) {

                $actionWasExecuted = true;
            }
        } catch (\Exception $exception) {
            logger(
                get_class($this),
                [
                    'exception' => $exception
                ]
            );
        }

        return $actionWasExecuted;
    }

    private function unlockCollectionCopy($loan, $collectionCopy)
    {
    }
}
