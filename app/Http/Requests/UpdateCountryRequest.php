<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCountryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $countryId = $this->route('country');

        return [
            'name' => [
                'required',
                Rule::unique('countries', 'name')->ignore($countryId),
            ],
        ];
    }
}
