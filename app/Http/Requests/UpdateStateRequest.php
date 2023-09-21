<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StateUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $stateId = $this->route('state')->id;

        return [
            'name' => [
                'required',
                Rule::unique('states', 'name')->ignore($stateId),
            ],
            'country_id' => 'required|exists:countries,id',
        ];
    }
}
