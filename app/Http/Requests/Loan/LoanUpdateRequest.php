<?php

namespace App\Http\Requests\Loan;

use Illuminate\Foundation\Http\FormRequest;

class LoanUpdateRequest extends FormRequest
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
            'loansIdentifier' => 'string|max:30',
            'returnDate' => 'nullable|after_or_equal:created_at',
            'expectedReturnDate' => 'after_or_equal:created_at',
            'observation' => 'string|max:200',

        ];
    }
}
