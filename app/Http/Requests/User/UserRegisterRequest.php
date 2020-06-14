<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'email',
            'password' => 'confirmed|min:8',
            'streetAddress' => 'string',
            'neighborhoodAddress' => 'string',
            'numberAddress' => 'string',
            'phoneNumber' => 'string',
            'cellNumber' => 'string',
            'complementAddress' => 'string',
            'photo' => 'string',
            'cpf' => 'required|size:14'
        ];
    }

    public function attributes()
    {
        return [
            'cpf' => 'CPF',
            'name' => 'Nome',
            'password' => 'Senha',
            'password_confirmation' => 'Senha de confirmação',
            'streetAddress' => 'Logradouro(Rua, avenida,etc.)',
            'neighborhoodAddress' => 'Bairro',
            'numberAddress' => 'Número',
            'phoneNumber' => 'Número do Telefone',
            'phoneNumber' => 'Número do Celular',
            'complementAddress' => 'Complemento',
            'photo' => 'Foto',
        ];
    }
}
