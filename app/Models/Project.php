<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'workspace_id'
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function scenarios()
    {
        return $this->hasMany(Scenario::class);
    }
}
