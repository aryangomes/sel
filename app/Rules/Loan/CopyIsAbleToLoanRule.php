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
        $copyIsAbleToLoan = false;

        $collectionCopy = $this->getCollectionCopyFromValue($value);

        if (key_exists('idCollectionCopy', $collectionCopy)) {

            $this->collectionCopy = CollectionCopy::find($collectionCopy['idCollectionCopy']);

            $copyIsAbleToLoan = $this->copyIsAbleToLoan();
        }
        return $copyIsAbleToLoan;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = 'Copy is not available';

        if ($this->collection) {
            $message = "Copy of {$this->collection->title} is not available";
        }
        return $message;
    }

    private function copyIsAbleToLoan()
    {
        $this->collection = $this->collectionCopy->collection;

        $quantityOfCopiesAvailable = $this->collection->quantityOfCopiesAvailable();

        $copyIsAbleToLoan = ($quantityOfCopiesAvailable > 0);

        return $copyIsAbleToLoan;
    }

    private function getCollectionCopyFromValue($value)
    {
        return
            key_exists(0, $value) ? $value[0] : $value;
    }
}
