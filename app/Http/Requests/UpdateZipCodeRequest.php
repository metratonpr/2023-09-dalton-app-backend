<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ZipCodeUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $zipCodeId = $this->route('zipcode')->id;

        return [
            'zipcode' => [
                'required',
                Rule::unique('zip_codes', 'zipcode')->ignore($zipCodeId),
            ],
            'place' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'neighborhood_id' => 'required|exists:neighborhoods,id',
        ];
    }
}
