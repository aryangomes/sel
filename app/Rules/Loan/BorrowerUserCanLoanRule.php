<?php

namespace App\Rules\Loan;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class BorrowerUserCanLoanRule implements Rule
{
    private $borrowerUser;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * The value is the Id of the User that is borrowing of the copy
     * 
     * @param  string  $attribute
     * @param  mixed  $value 
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->borrowerUser = User::find($value);

        if ($this->borrowerUser == null) {
            return false;
        }


        return $this->borrowerUserCanLoan();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = 'User is not allowed to realize the Loan.';

        if ($this->borrowerUser != null) {
            $message = "User {$this->borrowerUser->name} is not allowed to realize the Loan.";
        }
        return $message;
    }

    public function borrowerUserCanLoan()
    {

        $borrowerUserCanLoan =  ($this->borrowerUser->isActive && !($this->borrowerUser->isBlocked));

        return $borrowerUserCanLoan;
    }
}
