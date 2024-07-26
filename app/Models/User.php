<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory;

    protected $fillable=["id","name","email","password","password_confirmation","role"];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function incidents(){
        return $this->hasMany(Incident::class);
    }
    public function workspaces()
    {
        return $this->hasMany(Workspace::class);
    }

    
}
