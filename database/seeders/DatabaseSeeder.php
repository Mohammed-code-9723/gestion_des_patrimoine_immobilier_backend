<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            WorkspaceSeeder::class,
            ProjectSeeder::class,
            SiteSeeder::class,
            BuildingSeeder::class,
            ComponentSeeder::class,
            IncidentSeeder::class,
            ScenarioSeeder::class,
            MaintenanceTaskSeeder::class,
            ReportSeeder::class
        ]);
    }
}
