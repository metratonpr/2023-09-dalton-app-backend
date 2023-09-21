<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StateStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|unique:states,name',
            'country_id' => 'required|exists:countries,id',
        ];
    }
}
