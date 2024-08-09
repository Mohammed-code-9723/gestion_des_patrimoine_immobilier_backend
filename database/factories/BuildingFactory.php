<?php

namespace Database\Factories;
use App\Models\Site;
use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildingFactory extends Factory
{
    protected $model = Building::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z0-9]{5}'),
            'name' => $this->faker->name(),
            'activity' => $this->faker->word(),
            'address' => $this->faker->address(),
            'location' => json_encode([
                $this->faker->latitude(27.6, 35.9),
                $this->faker->longitude(-13.2, -1.0)
            ]),
            'year_of_construction' => $this->faker->year(),
            'surface' => $this->faker->randomFloat(2, 100, 10000),
            'type' => $this->faker->word(),
            'level_count' => $this->faker->numberBetween(1, 10),
            'site_id' => Site::pluck('id')->random(),
        ];
    }

}