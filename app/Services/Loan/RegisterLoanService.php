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
    private $data;

    public function __construct(

        LoanRepository $loanRepository
    ) {

        $this->loanRepository = $loanRepository;
    }

    public function __invoke($data)
    {
        info(
            get_class($this),
            [
                'data' => $data
            ]
        );
        $this->data = $data;
        $this->getVerifyBorrowerUserCanLoan();
        $this->getVerifyCopyIsAbleToLoan();
        $this->execute();
    }

    private  function execute()
    {
        $actionWasExecuted = false;
        try {

            if ($this->loanCanBeDone()) {

                $idCollectionCopy = $this->data['collectionCopy'];

                info(
                    get_class($this),
                    [
                        '$this->data' => $this->data,
                        'idCollectionCopy' => $idCollectionCopy,
                    ]
                );

                unset($this->data['collectionCopy']);

                //TODO REGISTER LOAN
                $this->loanRepository->create($this->data);

                $loan = $this->loanRepository->responseFromTransaction;

                info(
                    get_class($this),
                    [
                        'lockCollectionsCopies idCollectionCopy' => $idCollectionCopy
                    ]
                );
                foreach ($idCollectionCopy as $key => $value) {
                    info(
                        get_class($this),
                        [
                            'idCollectionCopy value' => $value['idCollectionCopy']
                        ]
                    );
                    //TODO LOCK THE COPIES BORROWED
                    $lockCollectionsCopies = new LockCollectionsCopies($loan, $value['idCollectionCopy']);
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
        $user = User::find($this->data['idBorrowerUser']);

        $verifyBorrowerUserCanLoan = new VerifyBorrowerUserCanLoanAction($user);

        $this->verifyBorrowerUserCanLoan = $verifyBorrowerUserCanLoan->borrowerUserCanLoan();
    }

    private function getVerifyCopyIsAbleToLoan()
    {
        info(
            get_class($this),
            [
                'variavel' => key_exists('collectionCopy', $this->data),
                '$this->data[collectionCopy]' => $this->data['collectionCopy'],
                '$this->data[collectionCopy]count' => count($this->data['collectionCopy']),
            ]
        );

        if (count($this->data['collectionCopy']) > 1) {

            $collectionCopy = CollectionCopy::find($this->data['collectionCopy'][0]['idCollectionCopy']);
        } else {
            foreach ($this->data['collectionCopy'] as $key => $idCollectionCopy) {

                $collectionCopy = CollectionCopy::find($idCollectionCopy['idCollectionCopy']);
            }
        }

        $verifyCopyIsAbleToLoan = new VerifyCopyIsAbleToLoanAction($collectionCopy);



        $this->verifyCopyIsAbleToLoan = $verifyCopyIsAbleToLoan->copyIsAbleToLoan();
    }

    public function loanCanBeDone()
    {
        //TODO VERIFY IF  USER BORROWER CAN TO LOAN
        $borrowerUserCanLoan = $this->verifyBorrowerUserCanLoan;

        //TODO VERIFY IF A COPY ABLE TO LOAN IT
        $copyIsAbleToLoan = $this->verifyCopyIsAbleToLoan;

        $loanCanBeDone = ($borrowerUserCanLoan && $copyIsAbleToLoan);

        return  $loanCanBeDone;
    }
}
