<?php

namespace App\Http\Requests\Loan;

use App\Rules\Loan\ReturnDateIsEqualOrLessExpectedReturnDateRule;
use Illuminate\Foundation\Http\FormRequest;

class LoanReturnRequest extends FormRequest
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
            'idLoan' => [
                'required', 'exists:loans,idLoan',
                new ReturnDateIsEqualOrLessExpectedReturnDateRule()
            ],
        ];
    }
}
