<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $cityId = $this->route('city');

        return [
            'name' => [
                'required',
                Rule::unique('cities', 'name')->ignore($cityId),
            ],
            'state_id' => 'required|exists:states,id',
        ];
    }
}
