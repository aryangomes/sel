<?php

namespace App\Actions\Loan;

use App\Models\CollectionCopy;
use App\Models\Loan;
use App\Models\LoanContainsCollectionCopy;
use App\Repositories\LoanContainsCollectionCopyRepository;

class LockCollectionsCopies
{
    private $loan, $collectionCopiesId, $loanContainsCollectionCopyRepository;


    public function __construct(Loan $loan, $collectionCopiesId)
    {
        $this->loan = $loan;
        $this->collectionCopiesId = $collectionCopiesId;
        $this->loanContainsCollectionCopyRepository = new LoanContainsCollectionCopyRepository(
            new LoanContainsCollectionCopy()
        );
    }

    public function lockCollectionCopies()
    {
        $collectionCopy = CollectionCopy::find($this->collectionCopiesId);

        $dataForLoanContainsCollectionCopy = [
            'idLoan' => $this->loan->idLoan,
            'idCollectionCopy' => $this->collectionCopiesId,
            'quantity' => 1,
        ];

        $this->loanContainsCollectionCopyRepository->create($dataForLoanContainsCollectionCopy);

        $this->becomeTheCollectionCopyUnavailable($collectionCopy);

        info(
            get_class($this),
            [
                'this' => $this->loanContainsCollectionCopyRepository,
                'dataForLoanContainsCollectionCopy' => $dataForLoanContainsCollectionCopy,
            ]
        );
    }

    private function becomeTheCollectionCopyUnavailable($collectionCopy)
    {
        $collectionCopy->changeToUnavailable();
        $collectionCopy->save();
    }
}
