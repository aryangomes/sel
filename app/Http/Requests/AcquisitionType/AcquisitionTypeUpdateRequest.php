<?php

namespace App\Http\Requests\AcquisitionType;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AcquisitionTypeUpdateRequest extends FormRequest
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
            'type' => 'required|max:100'
        ];
    }
}
