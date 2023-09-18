<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cpf_cnpj', 'rg_ie', 'email', 'phone'];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
 
}
