<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','warranty',
    'warranty_time', 'product_type_id'];

    public function productType(){
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function priceList(){
        return $this->hasMany(PriceList::class);
    }

}
