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
        return User::userMayToDoThisAction();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:200',
            'email' => 'email',
            'streetAddress' => 'required|string|max:200',
            'neighborhoodAddress' => 'required|string|max:200',
            'numberAddress' =>'required|string|max:20',
            'cellNumber' =>  'string|max:30',
            'phoneNumber' => 'string|max:30',
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
