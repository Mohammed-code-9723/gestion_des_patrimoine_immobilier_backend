<?php

namespace Database\Factories;

use App\Models\MaintenanceTask;
use App\Models\User;
use App\Models\Building;
use App\Models\Component;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceTask>
 */
class MaintenanceTaskFactory extends Factory
{
    protected $model = MaintenanceTask::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = $this->faker->randomElement(['Pending', 'In Progress', 'Completed']);
        
        return [
            'task_name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'priority' => $this->faker->randomElement(['Low', 'Medium', 'High']),
            'status' => $this->faker->randomElement(['Pending', 'In Progress', 'Completed']),
            'scheduled_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'completion_date' =>  $status === 'Completed' ? $this->faker->dateTimeBetween('now', '+1 year') : null,
            'user_id' => User::where('role','technicien')->pluck('id')->random(),
            'building_id' => Building::pluck('id')->random(),
            'component_id' => Component::pluck('id')->random(),
        ];
    }
}
