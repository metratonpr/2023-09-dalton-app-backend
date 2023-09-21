<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntityStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|unique:entities,name',
            'cpf_cnpj' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'rg_ie' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'email' => 'nullable|email|unique:entities,email', // Validação de e-mail único
            'phone' => 'nullable|string', // Adicione suas regras de validação específicas aqui
        ];
    }
}
