<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Workspace;
use App\Models\Site;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Site>
 */
class SiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Site::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z0-9]{5}'),
            'name' => $this->faker->word(),
            'activity' => $this->faker->word(),
            'address' => $this->faker->address(),
            'zipcode' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
            'floor_area' => $this->faker->numberBetween(1000, 10000),
            'workspace_id' => Workspace::pluck('id')->random(),
        ];
    }
}
