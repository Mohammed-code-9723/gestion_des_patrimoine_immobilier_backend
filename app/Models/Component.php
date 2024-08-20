<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'quantity',
        'unit',
        'last_rehabilitation_year',
        'condition',
        'severity_max',
        'risk_level',
        'description',
        'severity_safety',
        'severity_operations',
        'severity_work_conditions',
        'severity_environment',
        'severity_image',
        'building_id',
        'characteristics'
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
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
