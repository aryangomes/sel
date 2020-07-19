<?php

namespace App\Http\Requests\Lender;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class LenderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return User::userIsAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|required|string',
            'email' => 'email',
            'streetAddress' => 'sometimes|required|string',
            'neighborhoodAddress' => 'sometimes|required|string',
            'numberAddress' =>'sometimes|required|string',
            'phoneNumber' => 'string',
            'cellNumber' =>  'string',
            'complementAddress' =>  'string',
            'site' =>  'url',
        ];
    }
}
