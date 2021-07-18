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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->getBorrowerUser($value);

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

        if (isset($this->borrowerUser)) {
            $message = "User {$this->borrowerUser->name} is not allowed to realize the Loan.";
        }
        return $message;
    }
    private function getBorrowerUser($idUser)
    {
        $this->borrowerUser = User::find($idUser);
    }

    public function borrowerUserCanLoan()
    {

        $borrowerUserCanLoan =  ($this->borrowerUser->isActive && !($this->borrowerUser->isBlocked));

        return $borrowerUserCanLoan;
    }
}
