<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'isAvailable', 'store_id', 'product_id'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function budgetDetails()
    {
        return $this->hasMany(BudgetDetail::class);
    }
}
