<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Scenario;
use Illuminate\Database\Seeder;

class ScenarioSeeder extends Seeder
{
    public function run(): void
    {
        Scenario::factory()->count(10)->create();
    }
}