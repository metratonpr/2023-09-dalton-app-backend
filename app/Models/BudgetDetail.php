<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount', 'price', 'discount', 'subtotal', 'budget_id',
        'price_list_id'
    ];

    function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    function priceList()
    {
        return $this->belongsTo(PriceList::class);
    }
}
