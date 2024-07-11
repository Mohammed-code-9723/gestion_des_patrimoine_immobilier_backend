<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable=["id","name","email","password","role"];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function incidents(){
        return $this->hasMany(Incident::class);
    }
    public function conformities()
    {
        return $this->hasMany(Conformity::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
}
