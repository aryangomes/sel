<?php

namespace App\Rules\Loan;

use App\Models\Loan\Loan;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ReturnDateIsEqualOrLessExpectedReturnDateRule implements Rule
{
    private $loan;
    private $now;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->loan = null;
        $this->now = new Carbon();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $passes = false;


        $this->getLoan($value);

        if ($this->loan != null) {

            $expectedReturnDate = $this->loan->expectedReturnDate;

            $passes = $this->now->lessThanOrEqualTo($expectedReturnDate);
        }



        return $passes;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = 'The return date is greater than expected return date.';

        if ($this->loan != null) {
            $message =
                "The return date({$this->now}) is greater than expected return date({$this->loan->expectedReturnDate}).";
        }

        return $message;
    }

    private function getLoan($idLoan)
    {
        $this->loan = Loan::find($idLoan);
    }
}
