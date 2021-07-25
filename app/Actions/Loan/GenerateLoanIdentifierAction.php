<?php

namespace App\Actions\Loan;

use App\Models\Loan\Loan;
use App\Models\User;

class GenerateLoanIdentifierAction
{
    private $user, $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function __invoke()
    {
        $loan =
            factory(Loan::class)->create();
        $generatedLoanIdentifier = $loan->loansIdentifier;


        return $generatedLoanIdentifier;
    }
}
