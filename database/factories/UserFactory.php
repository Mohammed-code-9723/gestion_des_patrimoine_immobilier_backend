<?php
// database/factories/UserFactory.php

namespace Database\Factories;

use App\Models\User;
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
        $responsible=$this->getRandomResponsable($role);

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make($plainPassword),
            'password_confirmation' => $plainPassword,
            'role' => $role,
            'responsable' => $responsible,
            'permissions' => json_encode($permissions),
            'remember_token' => Str::random(10),
        ];
    }

    private function getRandomResponsable($role)
    {
        $responsable=null;
        
        switch ($role) {
            case 'superadmin':
                $responsable = null; // Superadmin doesn't have a responsable
                break;
            case 'admin':
                $responsable = User::where('role', 'superadmin')->inRandomOrder()->value('id');
                break;
            case 'manager':
                $responsable = User::whereIn('role', ['superadmin', 'admin'])->inRandomOrder()->value('id');
                break;
            case 'ingenieur':
                $responsable = User::whereIn('role', ['superadmin', 'admin', 'manager'])->inRandomOrder()->value('id');
                break;
            case 'technicien':
                $responsable = User::whereIn('role', ['superadmin', 'admin', 'manager', 'ingenieur'])->inRandomOrder()->value('id');
                break;
            default:
                $responsable = null;
        }

        return $responsable;
    }

    private function getPermissionsForRole($role)
    {
        $permissions = [
            'dashboard' => ['read']
        ];

        switch ($role) {
            case 'superadmin':
                $permissions['user_management'] = ['create', 'read', 'update', 'delete'];
                $permissions['workspaces'] = ['create', 'read', 'update', 'delete'];
                $permissions['projects'] = ['create', 'read', 'update', 'delete'];
                $permissions['sites'] = ['create', 'read', 'update', 'delete'];
                $permissions['buildings'] = ['create', 'read', 'update', 'delete'];
                $permissions['components'] = ['create', 'read', 'update', 'delete'];
                $permissions['incidents'] = ['create', 'read', 'update', 'delete'];
                $permissions['reports'] = ['create', 'read', 'update', 'delete'];
                $permissions['profile'] = ['create', 'read', 'update', 'delete'];
                $permissions['task_management'] = ['create', 'read', 'update', 'delete'];
                $permissions['settings'] = ['create', 'read', 'update', 'delete'];
                break;

            case 'admin':
                $permissions['user_management'] = ['create', 'read', 'update', 'delete'];
                $permissions['workspaces'] = ['create', 'read', 'update', 'delete'];
                $permissions['projects'] = ['create', 'read', 'update', 'delete'];
                $permissions['sites'] = ['create', 'read', 'update', 'delete'];
                $permissions['buildings'] = ['create', 'read', 'update', 'delete'];
                $permissions['components'] = ['create', 'read', 'update', 'delete'];
                $permissions['incidents'] = ['create', 'read', 'update', 'delete'];
                $permissions['reports'] = ['create', 'read', 'update', 'delete'];
                $permissions['profile'] = ['create', 'read', 'update', 'delete'];
                $permissions['task_management'] = ['create', 'read', 'update', 'delete'];
                break;

            case 'manager':
                $permissions['user_management'] = ['create', 'read', 'update', 'delete'];
                $permissions['workspaces'] = ['create', 'read', 'update', 'delete'];
                $permissions['projects'] = ['create', 'read', 'update', 'delete'];
                $permissions['sites'] = ['create', 'read', 'update', 'delete'];
                $permissions['buildings'] = ['create', 'read', 'update', 'delete'];
                $permissions['components'] = ['create', 'read', 'update', 'delete'];
                $permissions['incidents'] = ['create', 'read', 'update', 'delete'];
                $permissions['reports'] = ['create', 'read', 'update', 'delete'];
                $permissions['profile'] = ['create', 'read', 'update', 'delete'];
                $permissions['task_management'] = ['create', 'read', 'update', 'delete'];
                break;

            case 'ingenieur':
                $permissions['workspaces'] = ['create', 'read', 'update', 'delete'];
                $permissions['projects'] = ['create', 'read', 'update', 'delete'];
                $permissions['sites'] = ['create', 'read', 'update', 'delete'];
                $permissions['buildings'] = ['create', 'read', 'update', 'delete'];
                $permissions['components'] = ['create', 'read', 'update', 'delete'];
                $permissions['incidents'] = ['create', 'read', 'update', 'delete'];
                $permissions['reports'] = ['create', 'read', 'update', 'delete'];
                $permissions['profile'] = ['create', 'read', 'update', 'delete'];
                $permissions['task_management'] = ['create', 'read', 'update', 'delete'];
                break;

            case 'technicien':
                $permissions['workspaces'] = ['create', 'read', 'update', 'delete'];
                $permissions['sites'] = ['create', 'read', 'update', 'delete'];
                $permissions['buildings'] = ['create', 'read', 'update', 'delete'];
                $permissions['components'] = ['create', 'read', 'update', 'delete'];
                $permissions['incidents'] = ['create', 'read', 'update', 'delete'];
                $permissions['profile'] = ['create', 'read', 'update', 'delete'];
                break;
        }

        return $permissions;
    }
}
