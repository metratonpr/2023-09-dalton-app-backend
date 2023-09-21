<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $countryId = $this->route('country')->id;

        return [
            'name' => [
                'required',
                Rule::unique('countries', 'name')->ignore($countryId),
            ],
        ];
    }
}
