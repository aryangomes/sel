<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|string',
            'email' => 'sometimes|email',
            'password' => 'sometimes|confirmed|min:8',
            'streetAddress' => 'sometimes|string',
            'neighborhoodAddress' => 'sometimes|string',
            'numberAddress' => 'sometimes|string',
            'phoneNumber' => 'sometimes|string',
            'cellNumber' => 'sometimes|string',
            'complementAddress' => 'sometimes|string',
            'photo' => 'sometimes|string',
            'cpf' => 'sometimes|size:14'
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
