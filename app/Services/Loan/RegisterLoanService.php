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
        $this->getVerifyBorrowerUserCanLoan();
        // $this->getVerifyCopyIsAbleToLoan();
        $this->execute();
    }

    private  function execute()
    {
        $actionWasExecuted = false;
        try {

            if ($this->loanCanBeDone()) {

                $collectionCopies = $this->getCollectionCopies();

                $loanRegistered = $this->registerLoan();

                if ($this->loanRepository->transactionIsSuccessfully) {

                    $this->lockingCollectionCopy($loanRegistered, $collectionCopies);

                    $actionWasExecuted = true;

                    $this->generateLoanIdentifier($loanRegistered);
                }
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


    private function getVerifyBorrowerUserCanLoan()
    {
        $user = User::find($this->dataToRegisterLoan['idBorrowerUser']);

        $verifyBorrowerUserCanLoan = new VerifyBorrowerUserCanLoanAction($user);

        $this->verifyBorrowerUserCanLoan = $verifyBorrowerUserCanLoan->borrowerUserCanLoan();
    }



    public function loanCanBeDone()
    {
        //TODO VERIFY IF  USER BORROWER CAN TO LOAN
        $borrowerUserCanLoan = $this->verifyBorrowerUserCanLoan;

        //TODO VERIFY IF A COPY ABLE TO LOAN IT
        // $copyIsAbleToLoan = $this->verifyCopyIsAbleToLoan;

        $loanCanBeDone = ($borrowerUserCanLoan);

        return  $loanCanBeDone;
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
        $lockCollectionsCopies = new LockCollectionsCopies($loan, $collectionCopy['idCollectionCopy']);
        $lockCollectionsCopies->lockCollectionCopies();
    }

    private function lockingCollectionCopy($loan, $collectionCopies)
    {

        $collectionCopy =
            $this->getCollectionCopyFromInputValue($collectionCopies);

        if (key_exists(0, $collectionCopies)) {

            foreach ($collectionCopies as  $collectionCopy) {

                //TODO LOCK THE COPIES BORROWED
                $this->lockCollectionCopy($loan, $collectionCopy);
            }
        } else {

            //TODO LOCK THE COPIES BORROWED
            $this->lockCollectionCopy($loan, $collectionCopy);
        }
    }

    private function registerLoan()
    {
        //TODO REGISTER LOAN
        $this->loanRepository->create($this->dataToRegisterLoan);

        $loan = $this->loanRepository->responseFromTransaction;

        return $loan;
    }

    private function generateLoanIdentifier($loanRegistered)
    {
        //TODO GENERATE LOAN IDENTIFIER
        $generatedLoanIdentifier = new GenerateLoanIdentifierAction($loanRegistered);

        $loanRegistered->loansIdentifier = $generatedLoanIdentifier();

        $loanRegistered->save();
    }
}
