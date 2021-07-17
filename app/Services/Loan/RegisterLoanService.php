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

                $collectionCopies = $this->dataToRegisterLoan['collectionCopy'];

                $idCollectionCopy = $collectionCopies;

                $collectionCopy = key_exists(0, $collectionCopies) ?
                    $collectionCopies[0] : $collectionCopies;

                unset($this->dataToRegisterLoan['collectionCopy']);

                //TODO REGISTER LOAN
                $this->loanRepository->create($this->dataToRegisterLoan);

                $loan = $this->loanRepository->responseFromTransaction;
                if (key_exists(0, $collectionCopies)) {

                    foreach ($idCollectionCopy as $key => $value) {

                        //TODO LOCK THE COPIES BORROWED
                        $lockCollectionsCopies = new LockCollectionsCopies($loan, $value['idCollectionCopy']);
                        $lockCollectionsCopies->lockCollectionCopies();
                    }
                } else {

                    //TODO LOCK THE COPIES BORROWED
                    $lockCollectionsCopies = new LockCollectionsCopies($loan, $collectionCopy['idCollectionCopy']);
                    $lockCollectionsCopies->lockCollectionCopies();
                }


                //TODO GENERATE LOAN IDENTIFIER
                $generatedLoanIdentifier = new GenerateLoanIdentifierAction($loan);

                $loan->loansIdentifier = $generatedLoanIdentifier();

                $actionWasExecuted =  $loan->save();
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

    private function getVerifyCopyIsAbleToLoan()
    {


        $existCollectionCopy = $this->verifyIfHasCollectionCopy();

        if ($existCollectionCopy) {


            $collectionCopies = $this->dataToRegisterLoan['collectionCopy'];
            $collectionCopy = key_exists(0, $collectionCopies) ?
                $collectionCopies[0] : $collectionCopies;

            if (key_exists(0, $collectionCopies)) {
            } else {
                $copyIsAbleToLoanResult = [];

                foreach ($collectionCopies  as  $collectionCopy) {

                    $existIdCollectionCopy = $this->verifyIfHasIdCollectionCopy($collectionCopies);

                    if ($existIdCollectionCopy) {

                        $collectionCopy = CollectionCopy::find($collectionCopy['idCollectionCopy']);

                        $verifyCopyIsAbleToLoan = new VerifyCopyIsAbleToLoanAction($collectionCopy);

                        $copyIsAbleToLoanResult[$collectionCopy->idCollection] = $verifyCopyIsAbleToLoan->copyIsAbleToLoan();
                    }
                }
            }
        }

        $this->verifyCopyIsAbleToLoan = $copyIsAbleToLoanResult;
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
}
