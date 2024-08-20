<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'activity',
        'address',
        'location',
        'year_of_construction',
        'surface',
        'type',
        'level_count',
        'site_id',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function components()
    {
        return $this->hasMany(Component::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function maintenance_tasks()
    {
        return $this->hasMany(MaintenanceTask::class);
    }
}
