<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipCode extends Model
{
    use HasFactory;

    protected $fillable = ['zipcode', 'place', 'city_id', 'neighborhood_id'];

    public function city()
    {
        return   $this->belongsTo(City::class, 'city_id');
    }

    public function neighbordhood()
    {
        return $this->belongsTo(Neighborhood::class, 'neighborhood_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
