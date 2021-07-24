<?php

namespace App\Http\Requests\LoanContainsCollectionCopy;

use Illuminate\Foundation\Http\FormRequest;

class LoanContainsCollectionCopyUpdateRequest extends FormRequest
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
            'idLoan' => 'exists:loans,idLoan',
            'idCollectionCopy' => 'exists:collection_copies,idCollectionCopy',
        ];
    }
}
