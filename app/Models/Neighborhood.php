<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function zipcodes(){
        $this->hasMany(ZipCode::class);
    }


}
