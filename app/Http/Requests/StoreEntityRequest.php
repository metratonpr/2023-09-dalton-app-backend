<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
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
