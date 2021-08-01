<?php

namespace App\Services\Loan;

use App\Actions\Loan\GenerateLoanIdentifierAction;
use App\Actions\Loan\UnlockCollectionsCopies;
use App\Models\CollectionCopy;
use App\Models\Loan\LoanContainsCollectionCopy;
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

            $this->unlockCollectionCopies(
                $this->loanRepository->loan,
                $this->loanRepository->loan->containCopies
            );

            info(
                get_class($this),
                [
                    'loan' => $this->loanRepository->loan,
                    'copies' => $this->loanRepository->loan->containCopies,

                ]
            );

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
