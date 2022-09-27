<?php

namespace App\Rules\Loan;

use App\Models\CollectionCopy;
use Illuminate\Contracts\Validation\Rule;

class CopyIsAbleToLoanRule implements Rule
{

    private $collectionCopy;
    private $collection;

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

        $this->collectionCopy = CollectionCopy::find($value);


        if ($this->collectionCopy == null) {
            return false;
        }

        $copyCanBeBorrowed = $this->collectionHasCopiesAvailable() &&
            $this->copyIsAbleToLoan();

        return $copyCanBeBorrowed;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = 'Copy is not available to loan.';

        if ($this->collection) {
            $message = "Copy of {$this->collection->title} is not available to loan.";
        }
        return $message;
    }

    private function collectionHasCopiesAvailable()
    {
        $this->collection = $this->collectionCopy->collection;

        $quantityOfCopiesAvailable = $this->collection->quantityOfCopiesAvailable();

        $copyIsAbleToLoan = ($quantityOfCopiesAvailable > 0);

        return $copyIsAbleToLoan;
    }

    public function copyIsAbleToLoan()
    {
        return  $this->collectionCopy->isAvailable;
    }



    private function getCollectionCopyFromValue($value)
    {
        return
            key_exists(0, $value) ? $value[0] : $value;
    }
}
