<?php

namespace App\Actions\Loan;

use App\Models\CollectionCopy;
use App\Models\Loan\Loan;
use App\Models\Loan\LoanContainsCollectionCopy;
use App\Repositories\LoanContainsCollectionCopyRepository;

class UnlockCollectionsCopies
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

    public function unlockCollectionCopies()
    {
        $collectionCopy = CollectionCopy::find($this->collectionCopiesId);

        $this->becomeTheCollectionCopyAvailable($collectionCopy);
    }

    private function becomeTheCollectionCopyAvailable($collectionCopy)
    {
        $collectionCopy->changeToAvailable();
        $collectionCopy->save();
    }
}
