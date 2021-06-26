<?php

namespace App\Http\Requests\Provider;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ProviderUpdateRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:200',
            'email' => 'sometimes|email',
            'streetAddress' => 'sometimes|required|string|max:200',
            'neighborhoodAddress' => 'sometimes|required|string|max:200',
            'numberAddress' => 'sometimes|required|string|max:20',
            'phoneNumber' => 'sometimes|string|max:20',
            'cellNumber' =>  'sometimes|string|max:20',
            'complementAddress' =>  'sometimes|string',
        ];
    }
}
