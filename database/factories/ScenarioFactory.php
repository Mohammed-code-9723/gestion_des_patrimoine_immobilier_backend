<?php

namespace Database\Factories;


use App\Models\Project;
use App\Models\Scenario;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScenarioFactory extends Factory
{
    protected $model = Scenario::class;

    public function definition(): array
    {
        $start_year = $this->faker->numberBetween(2000, 2020);
        $end_year = $this->faker->numberBetween($start_year + 1, 2030);

        return [
            'name' => $this->faker->word(),
            'start_year' => $start_year,
            'end_year' => $end_year,
            'maintenance_strategy' => $this->faker->word(),
            'budgetary_constraint' => $this->faker->word(),
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
            'project_id' => Project::pluck('id')->random(),
        ];
    }
}