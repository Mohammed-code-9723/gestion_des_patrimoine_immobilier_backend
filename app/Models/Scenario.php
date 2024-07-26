<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
    use HasFactory;

    protected $fillable=['id','name','start_year','end_year','duration','maintenance_strategy','budgetary_constraint','status','project_id'];

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
