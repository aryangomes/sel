<?php

namespace App\Http\Requests\LoanContainsCollectionCopy;

use Illuminate\Foundation\Http\FormRequest;

class LoanContainsCollectionCopyRegisterRequest extends FormRequest
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
            'quantity' => 'required|numeric|min:1|max:1000000',
            'idLoan' => 'required',
            'idCollectionCopy' => 'required',
        ];
    }
}
