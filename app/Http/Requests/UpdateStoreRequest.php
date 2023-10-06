<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $storeId = $this->route('store');

        return [
            'name' => 'required',
            'contact' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'email' => [
                'nullable',
                'email',
                Rule::unique('stores', 'email')->ignore($storeId), // Validação de e-mail único, ignorando o registro atual
            ],
            'phone' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'cnpj' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'number' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'complement' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'address_id' => 'required|exists:addresses,id',
        ];
    }
}
