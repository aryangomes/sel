<?php

namespace App\Models\Loan;

use Illuminate\Database\Eloquent\Model;

class StatusLoan extends Model
{
    private const STATUS = [
        'pending',
        'returned',
        'canceled',
    ];

    private $loan;

    public function __construct(Loan $loan = null)
    {
        $this->loan = $loan;
    }

    static public function status()
    {
        $statusLoanUpper = collect(StatusLoan::STATUS)->map(function ($statusLoan) {
            return strtoupper($statusLoan);
        });

        return $statusLoanUpper;
    }

    public function setStatusLoanToPending()
    {
        $this->loan->status = StatusLoan::STATUS[0];
    }

    public function setStatusLoanToReturned()
    {
        $this->loan->status = StatusLoan::STATUS[1];
    }

    public function setStatusLoanToCanceled()
    {
        $this->loan->status = StatusLoan::STATUS[2];
    }
}
