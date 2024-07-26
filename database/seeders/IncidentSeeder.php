<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Incident;
use Illuminate\Database\Seeder;

class IncidentSeeder extends Seeder
{
    public function run(): void
    {
        Incident::factory()->count(10)->create();
    }
}