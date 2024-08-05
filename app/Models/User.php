<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens,HasFactory;
    protected $casts = [
        'permissions' => 'json', 
    ];

    protected $fillable=["id","name","email","password","password_confirmation","role","permissions"];
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

    public function activities(){
        return $this->hasMany(Activity::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to add to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
