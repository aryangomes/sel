<?php

namespace App\Actions\Loan;

use App\Models\CollectionCopy;
use App\Models\Loan\Loan;
use App\Models\Loan\LoanContainsCollectionCopy;
use App\Services\LoanContainsCollectionCopyService;

class LockCollectionsCopies
{
    private $loan, $collectionCopiesId, $loanContainsCollectionCopyService;


    public function __construct(Loan $loan, $collectionCopiesId)
    {
        $this->loan = $loan;
        $this->collectionCopiesId = $collectionCopiesId;
        $this->loanContainsCollectionCopyService = new LoanContainsCollectionCopyService(
            new LoanContainsCollectionCopy()
        );
    }

    public function lockCollectionCopies()
    {
        $collectionCopy = CollectionCopy::find($this->collectionCopiesId);

        $dataForLoanContainsCollectionCopy = [
            'idLoan' => $this->loan->idLoan,
            'idCollectionCopy' => $this->collectionCopiesId
        ];

        $this->loanContainsCollectionCopyService->create($dataForLoanContainsCollectionCopy);

        $this->becomeTheCollectionCopyUnavailable($collectionCopy);
    }

    private function becomeTheCollectionCopyUnavailable($collectionCopy)
    {
        $collectionCopy->changeToUnavailable();
        $collectionCopy->save();
    }
}
