<?php

namespace App\Services\Loan;

use App\Actions\Loan\GenerateLoanIdentifierAction;
use App\Actions\Loan\LockCollectionsCopies;
use App\Actions\Loan\VerifyBorrowerUserCanLoanAction;
use App\Actions\Loan\VerifyCopyIsAbleToLoanAction;
use App\Models\CollectionCopy;
use App\Models\Loan\Loan;
use App\Models\User;
use App\Services\LoanService;

class RegisterLoanService
{
    /**
     * 
     * @property LoanService $loanService
     */
    private $loanService;

    /**
     * 
     * @property array $dataToRegisterLoan
     */
    private $dataToRegisterLoan;


    /**
     * @property string $keyIndexCollectionCopy
     */
    private $keyIndexCollectionCopy;

    public function __construct(

        LoanService $loanService
    ) {

        $this->loanService = $loanService;
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

            if ($this->loanService->transactionIsSuccessfully) {

                $this->lockingCollectionCopies($loanRegistered, $collectionCopies);

                $actionWasExecuted = true;

                $this->generateLoanIdentifier($loanRegistered);


                $loanRegistered->setStatusLoanToInLoan();
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

        if (key_exists('collectionCopy', $this->dataToRegisterLoan)) {
            $this->keyIndexCollectionCopy = 'collectionCopy';
            return true;
        }

        if (key_exists('idCollectionCopy', $this->dataToRegisterLoan)) {
            $this->keyIndexCollectionCopy = 'idCollectionCopy';
            return true;
        }

        return false;
    }

    private function verifyIfHasIdCollectionCopy($collectionCopy)
    {
        $verifyIfHasIdCollectionCopy = key_exists('idCollectionCopy', $collectionCopy);

        return $verifyIfHasIdCollectionCopy;
    }

    private function getCollectionCopyFromInputValue($inputValue)
    {
        if (!is_array($inputValue)) {
            return $inputValue;
        }
        return key_exists(0, $inputValue) ? $inputValue[0] : $inputValue;
    }

    private function getCollectionCopies()
    {
        $collectionCopies = null;

        if ($this->verifyIfHasCollectionCopy()) {


            if ($this->collectionCopyFieldIsArray()) {
                $collectionCopies = $this->dataToRegisterLoan[$this->keyIndexCollectionCopy];
            } else {
                $collectionCopies[][$this->keyIndexCollectionCopy] = $this->dataToRegisterLoan[$this->keyIndexCollectionCopy];
            }

            unset($this->dataToRegisterLoan[$this->keyIndexCollectionCopy]);
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



        if (is_array($collectionCopies)) {
            $this->lockCollectionCopies($loan, $collectionCopies);
        } else {
            $collectionCopy =
                $this->getCollectionCopyFromInputValue($collectionCopies);

            $this->lockCollectionCopy($loan, $collectionCopy);
        }
    }

    private function registerLoan()
    {

        $this->dataToRegisterLoan = array_merge(
            $this->dataToRegisterLoan,
            [
                'expectedReturnDate' =>
                today()->addDays(config('loan.expectedReturnDays', 7))
            ]
        );

        $this->loanService->create($this->dataToRegisterLoan);

        $loan = $this->loanService->responseFromTransaction;

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

    private function collectionCopyFieldIsArray()
    {
        return $this->keyIndexCollectionCopy == 'collectionCopy';
    }
}
