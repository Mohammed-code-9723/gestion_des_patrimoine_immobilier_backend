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
        'year_of_construction',
        'surface',
        'type',
        'level_count',
        'site_id',
        'structure_state',        
        'electricity_inventory',  
        'plumbing_state',         
        'cvc_state',              
        'fire_safety_evaluation', 
        'elevator_escalator_state'
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

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function conformities()
    {
        return $this->hasMany(Conformity::class);
    }

    public function works()
    {
        return $this->hasMany(Work::class);
    }
}
