<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Incident;
use App\Models\MaintenanceTask;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'report_about' => 'Building',
            'description' => $this->faker->paragraph,
            'created_by' => User::pluck('id')->random(),
            'project_id' =>$this->faker->randomElement([User::pluck('id')->random(),null]),
            'site_id' => $this->faker->randomElement([Project::pluck('id')->random(),null]),
            'building_id' => $this->faker->randomElement([Building::pluck('id')->random(),null]),
            'incident_id' => $this->faker->randomElement([Incident::pluck('id')->random(),null]),
            'maintenance_id' => $this->faker->randomElement([MaintenanceTask::pluck('id')->random(),null]),
        ];
    }
}
