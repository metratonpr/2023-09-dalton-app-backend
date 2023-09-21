<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceListUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $priceListId = $this->route('price_list')->id;

        return [
            'price' => 'required|numeric|min:0',
            'isAvailable' => 'required|boolean',
            'store_id' => 'required|exists:stores,id',
            'product_id' => 'required|exists:products,id',
        ];
    }
}
