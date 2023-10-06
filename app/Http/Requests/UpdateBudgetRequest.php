<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBudgetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $budgetId = $this->route('budget');

        return [
            'number' => [
                'required',
                Rule::unique('budgets', 'number')->ignore($budgetId),
            ],
            'budget_date' => 'required|date',
            'expiration_date' => 'required|date',
            'delivery_date' => 'required|date',
            'shipping_value' => 'required|numeric',
            'address_id' => 'required|exists:addresses,id',
            'budget_type_id' => 'required|exists:budget_types,id',
        ];
    }
}
