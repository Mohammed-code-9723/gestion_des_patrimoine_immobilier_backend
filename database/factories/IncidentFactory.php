<?php

namespace Database\Factories;


use App\Models\User;
use App\Models\Building;
use App\Models\Component;
use App\Models\Incident;
use App\Models\Project;
use App\Models\Site;
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
            'critical' => $this->faker->randomElement([true,false]),
            'user_id' => User::pluck('id')->random(),
            'building_id' => Building::pluck('id')->random(),
            'component_id' => Component::pluck('id')->random(),
            'project_id' => Project::pluck('id')->random(),
            'site_id' => Site::pluck('id')->random(),
        ];
    }
}
