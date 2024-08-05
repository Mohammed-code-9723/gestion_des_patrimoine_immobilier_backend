<?php
// database/factories/UserFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $plainPassword = fake()->password();
        $role = fake()->randomElement(["admin", "superadmin", "technicien", "ingenieur", "manager"]);
        $permissions = $this->getPermissionsForRole($role);

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make($plainPassword),
            'password_confirmation' => $plainPassword,
            'role' => $role,
            'permissions' => json_encode($permissions),
            'remember_token' => Str::random(10),
        ];
    }

    private function getPermissionsForRole($role)
    {
        $permissions = [
            'dashboard' => ['read']
        ];

        switch ($role) {
            case 'superadmin':
                $permissions['workspaces'] = ['create', 'read', 'update', 'delete'];
                $permissions['projects'] = ['create', 'read', 'update', 'delete'];
                $permissions['sites'] = ['create', 'read', 'update', 'delete'];
                $permissions['buildings'] = ['create', 'read', 'update', 'delete'];
                $permissions['components'] = ['create', 'read', 'update', 'delete'];
                $permissions['incidents'] = ['create', 'read', 'update', 'delete'];
                $permissions['settings'] = ['read', 'update'];
                $permissions['user_management'] = ['create', 'read', 'update', 'delete'];
                break;

            case 'admin':
                $permissions['workspaces'] = ['create', 'read', 'update', 'delete'];
                $permissions['projects'] = ['create', 'read', 'update', 'delete'];
                $permissions['sites'] = ['create', 'read', 'update', 'delete'];
                $permissions['buildings'] = ['read'];
                $permissions['components'] = ['read'];
                $permissions['incidents'] = ['create', 'read', 'update', 'delete'];
                $permissions['reports'] = ['create', 'read'];
                break;

            case 'manager':
                $permissions['projects'] = ['create', 'read', 'update', 'delete'];
                $permissions['sites'] = ['create', 'read', 'update', 'delete'];
                $permissions['buildings'] = ['read'];
                $permissions['components'] = ['read'];
                $permissions['incidents'] = ['create', 'read', 'update', 'delete'];
                break;

            case 'ingenieur':
                $permissions['sites'] = ['read'];
                $permissions['buildings'] = ['read'];
                $permissions['components'] = ['read'];
                $permissions['incidents'] = ['create', 'read', 'update'];
                $permissions['reports'] = ['read'];
                break;

            case 'technicien':
                $permissions['sites'] = ['read'];
                $permissions['components'] = ['read'];
                $permissions['incidents'] = ['create', 'read', 'update'];
                $permissions['maintenance'] = ['read', 'create', 'update'];
                break;
        }

        return $permissions;
    }
}
