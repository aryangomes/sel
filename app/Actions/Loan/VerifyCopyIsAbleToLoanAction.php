<?php

namespace App\Actions\Loan;

use App\Models\CollectionCopy;

class VerifyCopyIsAbleToLoanAction
{

    private $collectionCopy;

    public function __construct(CollectionCopy $collectionCopy)
    {
        $this->collectionCopy = $collectionCopy;
    }
    public function __invoke()
    {
        // $this->copyIsAbleToLoan();
    }

    public function copyIsAbleToLoan()
    {
        $collection = $this->collectionCopy->collection;


        $quantityOfCopiesAvailable = $collection->quantityOfCopiesAvailable();


        $copyIsAbleToLoan = ($quantityOfCopiesAvailable > 0);

        return $copyIsAbleToLoan;
    }
}
