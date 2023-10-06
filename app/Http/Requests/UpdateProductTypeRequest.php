<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $productTypeId = $this->route('product_type');

        return [
            'name' => [
                'required',
                Rule::unique('product_types', 'name')->ignore($productTypeId),
            ],
        ];
    }
}
