<?php

namespace Database\Factories;


use App\Models\User;
use App\Models\Building;
use App\Models\Component;
use App\Models\Incident;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncidentFactory extends Factory
{
    protected $model = Incident::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['Open', 'InProgress', 'Closed']),
            'user_id' => User::pluck('id')->random(),
            'building_id' => Building::pluck('id')->random(),
            'component_id' => Component::pluck('id')->random(),
        ];
    }
}
