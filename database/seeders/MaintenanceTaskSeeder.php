<?php

namespace Database\Seeders;

use App\Models\MaintenanceTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class MaintenanceTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MaintenanceTask::factory()->count(50)->create();
    }
}
