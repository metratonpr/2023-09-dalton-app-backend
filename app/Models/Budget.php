<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable =['number','budget_date','expiration_date',
    'delivery_date','shipping_value','address_id','budget_type_id'];

    public function addresses(){
        return $this->belongsTo(Address::class,'address_id');
    }

    public function budget_type(){
        return $this->belongsTo(BudgetType::class,'budget_type_id');
    }

    public function budget_details(){
        return $this->hasMany(BudgetDetail::class);
    }
}
