<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNeighborhoodRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        //www.seusite.com/neighborhoods/1
        $neighborhoodId = $this->route('neighborhood');

        return [
            'name' => [
                'required',
                Rule::unique('neighborhoods', 'name')->ignore($neighborhoodId),
            ],
        ];
    }
}
