<?php

namespace App\Traits\Loan;


trait StatusLoanTrait
{
    private $statusOfLoan = [
        'pending',
        'returned',
        'canceled',
    ];


    static public function status()
    {
        $statusOfLoan = self::$statusOfLoan;

        return $statusOfLoan;
    }

    public function setStatusLoanToPending()
    {
        $this->status = $this->statusOfLoan[0];
    }

    public function setStatusLoanToReturned()
    {
        $this->status = $this->statusOfLoan[1];
    }

    public function setStatusLoanToCanceled()
    {
        $this->status = $this->statusOfLoan[2];
    }
}
