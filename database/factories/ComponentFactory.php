<?php

namespace Database\Factories;


use App\Models\Building;
use App\Models\Component;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComponentFactory extends Factory
{
    protected $model = Component::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z0-9]{5}'),
            'name' => $this->faker->word(),
            'quantity' => $this->faker->randomFloat(2, 1, 100),
            'unit' => $this->faker->word(),
            'last_rehabilitation_year' => $this->faker->year(),
            'condition' => $this->faker->randomElement(['C1', 'C2', 'C3', 'C4']),
            'severity_max' => $this->faker->randomElement(['S1', 'S2', 'S3', 'S4']),
            'risk_level' => $this->faker->randomElement(['R1', 'R2', 'R3', 'R4']),
            'description' => $this->faker->sentence(),
            'building_id' => Building::pluck('id')->random(),
        ];
    }
}
