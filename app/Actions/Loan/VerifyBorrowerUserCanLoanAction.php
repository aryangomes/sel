<?php

namespace App\Actions\Loan;

use App\Models\User;

class VerifyBorrowerUserCanLoanAction
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function borrowerUserCanLoan()
    {

        $borrowerUserCanLoan =  ($this->user->isActive && !($this->user->isBlocked));

        return $borrowerUserCanLoan;
    }
}
