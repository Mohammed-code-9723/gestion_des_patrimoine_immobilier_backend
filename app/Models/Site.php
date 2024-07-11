<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'activity',
        'address',
        'zipcode',
        'city',
        'country',
        'floor_area',
    ];

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }
}
