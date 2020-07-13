<?php

namespace App\Http\Requests\Lender;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class LenderRegisterRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'email',
            'streetAddress' => 'required|string',
            'neighborhoodAddress' => 'required|string',
            'numberAddress' =>'required|string',
            'phoneNumber' => 'string',
            'cellNumber' =>  'string',
            'complementAddress' =>  'string',
            'site' =>  'url',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            // 
        ];
    }
}
