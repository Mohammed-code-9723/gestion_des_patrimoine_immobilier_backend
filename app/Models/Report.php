<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'report_about',
        'description',
        'created_by',
        'project_id',
        'site_id',
        'building_id',
        'incident_id',
        'maintenance_id',

    ];

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
