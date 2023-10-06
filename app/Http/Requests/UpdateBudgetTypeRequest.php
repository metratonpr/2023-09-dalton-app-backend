<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBudgetTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $budgetTypeId = $this->route('budget_type')->id;

        return [
            'name' => [
                'required',
                Rule::unique('budget_types', 'name')->ignore($budgetTypeId),
            ],
        ];
    }
}
