<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'contact' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'email' => 'nullable|email|unique:stores,email', // Validação de e-mail único
            'phone' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'cnpj' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'number' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'complement' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'address_id' => 'required|exists:addresses,id',
        ];
    }
}
