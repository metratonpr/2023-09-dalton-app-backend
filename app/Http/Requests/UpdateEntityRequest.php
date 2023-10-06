<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEntityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $entitiesId = $this->route('neighborhood');
        return [
            'name' => [
                'required',
                Rule::unique('entities', 'name')->ignore($entitiesId),
            ],
            'cpf_cnpj' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'rg_ie' => 'nullable|string', // Adicione suas regras de validação específicas aqui
            'email' => 'nullable|email|unique:entities,email', // Validação de e-mail único
            'phone' => 'nullable|string', // Adicione suas regras de validação específicas aqui
        ];
    }
}
