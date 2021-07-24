<?php

namespace App\Http\Requests\Collection;

use Illuminate\Foundation\Http\FormRequest;

class CollectionUpdateRequest extends FormRequest
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
            'title' => 'required|max:200|string',
            'author' => 'required|max:200|string',
            'cdd' => 'max:20|string|nullable',
            'cdu' => 'max:20|string|nullable',
            'isbn' => 'max:20|string|nullable',
            'publisherCompany' => 'max:150|string|nullable',
            'idCollectionType' => 'exists:collection_types,idCollectionType',
            'idCollectionCategory' => 'exists:collection_categories,idCollectionCategory',
            'idAcquisition' => 'exists:acquisitions,idAcquisition',

        ];
    }
}
