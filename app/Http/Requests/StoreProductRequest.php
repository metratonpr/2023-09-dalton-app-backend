<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'warranty' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'warranty_time' => 'nullable|numeric|min:0', // Adicione suas regras de validação específicas aqui
            'product_type_id' => 'required|exists:product_types,id',
        ];
    }
}
