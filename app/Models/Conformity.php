<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conformity extends Model
{
    use HasFactory;
    protected $fillable = [
        'building_id',
        'type',
        'date_verification',
        'result',
        'user_id'
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
