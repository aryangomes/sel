<?php

namespace App\Traits\Loan;


trait StatusLoanTrait
{
    static private $PENDING =  'pending';
    static private $IN_LOAN =  'in_loan';
    static private $RETURNED =  'returned';
    static private $CANCELED =  'canceled';


    static public function status()
    {
        $statusOfLoan = [
            self::$PENDING,
            self::$IN_LOAN,
            self::$RETURNED,
            self::$CANCELED,
        ];

        return $statusOfLoan;
    }

    public function setStatusLoanToPending()
    {
        if ($this->canSetStatusLoanToPending()) {

            $this->status = self::$PENDING;

            $this->save();
        }
    }

    public function setStatusLoanToInLoan()
    {
        if ($this->canSetStatusLoanToInLoan()) {
            $this->status = self::$IN_LOAN;
            $this->save();
        }
    }

    public function setStatusLoanToReturned()
    {
        if ($this->canSetStatusLoanToReturned()) {
            $this->status =
                self::$RETURNED;
            $this->save();
        }
    }

    public function setStatusLoanToCanceled()
    {
        if ($this->canSetStatusLoanToCanceled()) {
            $this->status = self::$CANCELED;
            $this->save();
        }
    }

    public function isPending()
    {
        return $this->status == self::$PENDING;
    }

    public function isInLoan()
    {
        return $this->status == self::$IN_LOAN;
    }

    public function isReturned()
    {
        return $this->status == self::$RETURNED;
    }

    public function isCanceled()
    {
        return $this->status == self::$CANCELED;
    }

    private function canSetStatusLoanToPending()
    {
        $canSetStatusLoanToPending =
            !($this->isCanceled() && $this->isInLoan()
                && $this->isReturned());

        return  $canSetStatusLoanToPending;
    }

    private function canSetStatusLoanToInLoan()
    {
        $canSetStatusLoanToInLoan =
            ($this->isPending() &&
                !($this->isCanceled() && $this->isReturned()));

        return  $canSetStatusLoanToInLoan;
    }

    private function canSetStatusLoanToReturned()
    {
        $canSetStatusLoanToReturned =
            ($this->isInLoan() &&
                !($this->isCanceled() && $this->isPending()));

        return  $canSetStatusLoanToReturned;
    }

    private function canSetStatusLoanToCanceled()
    {
        $canSetStatusLoanToCanceled =
            (!$this->isReturned() &&
                ($this->isInLoan() || $this->isPending()));

        return  $canSetStatusLoanToCanceled;
    }
}
