<?php

namespace App\Http\Requests\Loan;

use App\Rules\Loan\CopyIsAbleToLoanRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'idOperatorUser' => 'required',
            'idBorrowerUser' => 'required',
            'collectionCopy' => ['required', 'array', new CopyIsAbleToLoanRule()],
            'idCollectionCopy.*' => ['required'],
        ];
    }
}
