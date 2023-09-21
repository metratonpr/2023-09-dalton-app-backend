<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NeighborhoodUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $neighborhoodId = $this->route('neighborhood')->id;

        return [
            'name' => [
                'required',
                Rule::unique('neighborhoods', 'name')->ignore($neighborhoodId),
            ],
        ];
    }
}
