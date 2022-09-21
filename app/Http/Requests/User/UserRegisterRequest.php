<?php

namespace App\Http\Requests\User;

use App\Models\User;
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
            'name' => 'required|max:200',
            'email' => 'email',
            'password' => 'required|confirmed|min:8',
            'streetAddress' => 'string|max:200',
            'neighborhoodAddress' => 'string|max:200',
            'numberAddress' => 'string|max:30',
            'phoneNumber' => 'string|max:30',
            'cellNumber' => 'string|max:30',
            'complementAddress' => 'string',
            'photo' => 'string',
            'cpf' => 'required|size:11'
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
