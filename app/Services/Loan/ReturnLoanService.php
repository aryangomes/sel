<?php

namespace App\Services\Loan;

use App\Actions\Loan\GenerateLoanIdentifierAction;
use App\Actions\Loan\UnlockCollectionsCopies;
use App\Models\CollectionCopy;
use App\Models\Loan\LoanContainsCollectionCopy;
use App\Models\User;
use App\Services\LoanService;
use Carbon\Carbon;

class ReturnLoanService
{

    private $loanService;

    public function __construct(

        LoanService $loanService
    ) {

        $this->loanService = $loanService;
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

            $this->loanService->update($dataToUpdate, $this->loanService->loan);

            // $this->loanService->loan->setStatusLoanToReturned();

            $this->unlockCollectionCopies(
                $this->loanService->loan,
                $this->loanService->loan->containCopies
            );

            info(
                get_class($this),
                [
                    'loan' => $this->loanService->loan,
                    'copies' => $this->loanService->loan->containCopies,

                ]
            );

            if ($this->loanService->transactionIsSuccessfully) {

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
        $lockCollectionsCopies = new UnlockCollectionsCopies($loan, $collectionCopy['idCollectionCopy']);

        $lockCollectionsCopies->unlockCollectionCopies();
    }

    public function unlockCollectionCopies($loan, $collectionCopies)
    {
        foreach ($collectionCopies as  $collectionCopy) {


            $this->unlockCollectionCopy($loan, $collectionCopy);
            info(
                get_class($this),
                [
                    'unlockCollectionCopy' => $collectionCopy->collectionCopy
                ]
            );
        }
    }
}
