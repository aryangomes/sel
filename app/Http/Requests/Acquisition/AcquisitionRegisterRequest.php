<?php

namespace App\Http\Requests\Acquisition;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AcquisitionRegisterRequest extends FormRequest
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
            'price' => 'required|numeric|min:0|max:1000000',
            'quantity' => 'required|numeric|min:1|max:1000000',
            'idLender' => 'required',
            'idProvider' => 'required',
            'idAcquisitionType' => 'required',
        ];
    }
}
