<?php

namespace App\Http\Requests\Loan;

use App\Rules\Loan\BorrowerUserCanLoanRule;
use App\Rules\Loan\CopyIsAbleToLoanRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoanRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'returnDate' => 'nullable|after_or_equal:today',
            'expectedReturnDate' => 'after_or_equal:today',
            'observation' => 'required|string|max:200',
            'idOperatorUser' => ['required', 'exists:users,id'],
            'idBorrowerUser' =>
            [
                'required', 'exists:users,id',
                new BorrowerUserCanLoanRule()
            ],
            'collectionCopy' => [
                Rule::requiredIf(!$this->has('idCollectionCopy')),
                'array'
            ],
            'collectionCopy.*.idCollectionCopy' => ['required_with:collectionCopy', 'exists:collection_copies,idCollectionCopy', new CopyIsAbleToLoanRule()],

            'idCollectionCopy' => [
                Rule::requiredIf(!$this->has('collectionCopy')),
                'exists:collection_copies,idCollectionCopy', new CopyIsAbleToLoanRule()
            ],
        ];
    }
}
