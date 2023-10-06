<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $productId = $this->route('product');

        return [
            'name' => [
                'required',
                Rule::unique('neighborhoods', 'name')->ignore($productId),
            ],
            'description' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'warranty' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'warranty_time' => 'nullable|numeric|min:0', // Adicione suas regras de validação específicas aqui
            'product_type_id' => 'required|exists:product_types,id',
        ];
    }
}
