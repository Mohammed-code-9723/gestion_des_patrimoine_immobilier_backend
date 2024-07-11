<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'building_id',
        'component_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
