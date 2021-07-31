<?php

namespace App\Services\Loan;

use App\Actions\Loan\GenerateLoanIdentifierAction;
use App\Actions\Loan\LockCollectionsCopies;
use App\Actions\Loan\VerifyBorrowerUserCanLoanAction;
use App\Actions\Loan\VerifyCopyIsAbleToLoanAction;
use App\Models\CollectionCopy;
use App\Models\User;
use App\Repositories\LoanRepository;

class RegisterLoanService
{
    private $verifyBorrowerUserCanLoan;
    private $verifyCopyIsAbleToLoan;
    private $loanRepository;
    private $dataToRegisterLoan;

    public function __construct(

        LoanRepository $loanRepository
    ) {

        $this->loanRepository = $loanRepository;
    }

    public function __invoke($dataToRegisterLoan)
    {

        $this->dataToRegisterLoan = $dataToRegisterLoan;

        $this->execute();
    }

    private  function execute()
    {
        $actionWasExecuted = false;

        try {

            $collectionCopies = $this->getCollectionCopies();

            $loanRegistered = $this->registerLoan();

            if ($this->loanRepository->transactionIsSuccessfully) {

                $this->lockingCollectionCopies($loanRegistered, $collectionCopies);

                $actionWasExecuted = true;

                $this->generateLoanIdentifier($loanRegistered);
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

    private function verifyIfHasCollectionCopy()
    {
        $verifyIfHasCollectionCopy = key_exists('collectionCopy', $this->dataToRegisterLoan);

        return $verifyIfHasCollectionCopy;
    }

    private function verifyIfHasIdCollectionCopy($collectionCopy)
    {
        $verifyIfHasIdCollectionCopy = key_exists('idCollectionCopy', $collectionCopy);

        return $verifyIfHasIdCollectionCopy;
    }

    private function getCollectionCopyFromInputValue($inputValue)
    {
        return key_exists(0, $inputValue) ? $inputValue[0] : $inputValue;
    }

    private function getCollectionCopies()
    {
        $collectionCopies = null;

        if ($this->verifyIfHasCollectionCopy()) {

            $collectionCopies = $this->dataToRegisterLoan['collectionCopy'];

            unset($this->dataToRegisterLoan['collectionCopy']);
        }


        return $collectionCopies;
    }

    private function lockCollectionCopy($loan, $collectionCopy)
    {
        if ($this->verifyIfHasIdCollectionCopy($collectionCopy)) {

            $lockCollectionsCopies = new LockCollectionsCopies($loan, $collectionCopy['idCollectionCopy']);

            $lockCollectionsCopies->lockCollectionCopies();
        }
    }

    private function lockingCollectionCopies($loan, $collectionCopies)
    {

        $collectionCopy =
            $this->getCollectionCopyFromInputValue($collectionCopies);


        $collectionCopiesIsArray = key_exists(0, $collectionCopies);

        if ($collectionCopiesIsArray) {
            $this->lockCollectionCopies($loan, $collectionCopies);
        } else {

            $this->lockCollectionCopy($loan, $collectionCopy);
        }
    }

    private function registerLoan()
    {
        $this->loanRepository->create($this->dataToRegisterLoan);

        $loan = $this->loanRepository->responseFromTransaction;
        $loan->setStatusLoanToPending();

        return $loan;
    }

    private function generateLoanIdentifier($loanRegistered)
    {
        $generatedLoanIdentifier = new GenerateLoanIdentifierAction($loanRegistered);

        $loanRegistered->loansIdentifier = $generatedLoanIdentifier();

        $loanRegistered->save();
    }

    public function lockCollectionCopies($loan, $collectionCopies)
    {
        foreach ($collectionCopies as  $collectionCopy) {


            $this->lockCollectionCopy($loan, $collectionCopy);
        }
    }
}
