<?php

namespace App\Http\Requests\Provider;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProviderRegisterRequest extends FormRequest
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
            'numberAddress' => 'required|string|max:20',
            'phoneNumber' => 'string|max:30',
            'cellNumber' =>  'string|max:30',
            'complementAddress' =>  'string',
            'cpf' => [Rule::requiredIf(!$this->has('cnpj')), 'size:11'],
            'cnpj' => [Rule::requiredIf(!$this->has('cpf')), 'size:14'],
        ];
    }
}
