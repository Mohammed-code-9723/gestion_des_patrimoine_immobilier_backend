<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;
    protected $fillable = [
        'building_id',
        'date',
        'description',
        'cost',
        'provider',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
