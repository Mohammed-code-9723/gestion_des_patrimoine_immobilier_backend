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
            'name' => $this->faker->randomElement([
                'Appareils sanitaires', 
                'Ascenseurs', 
                'Couverture', 
                'Electricité',
                'Isolation thermique de plancher',
                'Isolation thermique des murs',
                'Isolation thermique de toiture',
                'Menuiseries extérieures, baies vitrées, verrières',
                'Plomberie',
                'Production de chaud',
                'Revêtements des sols, murs et plafonds',
                'Sécurité et protection incendie',
                'Structure',
                'Ventilation',
                'Revêtements de façade'
            ]),
            'quantity' => $this->faker->randomFloat(2, 1, 100),
            'unit' => $this->faker->randomElement([
                'm² (Surface)',
                'm² (eq. floor area)',
                'ml (Volume)',
                'Unit (custom unit)',
            ]),
            'last_rehabilitation_year' => $this->faker->year(),
            'condition' => $this->faker->randomElement(['C1', 'C2', 'C3', 'C4']),
            'severity_max' => $this->faker->randomElement(['S1', 'S2', 'S3', 'S4']),
            'risk_level' => $this->faker->randomElement(['R1', 'R2', 'R3', 'R4']),
            'description' => $this->faker->sentence(),
            'characteristics' => $this->faker->randomElement([
                'Etanchéité de toiture-terrasse - - normal',
                'Ardoise - - normal',
                'Bacs acier - - normal',
                'Bacs aluminium - - normal',
                'Bardeau bitumineux - - normal',
                'Couverture cuivre ou zinc - - normal',
                'Tuiles - - normal',
                'Laiton - - normal',
                'Plâtre - - normal',
                'Céramique - - normal',
                'PVC - - normal',
                'Carrelage - - normal',
                'Parquet - - normal',
                'Mosaïque - - normal',
                'Granite - - normal',
                'Marbre - - normal',
            ]),
            'severity_safety' => $this->faker->randomElement(['S1', 'S2', 'S3', 'S4']),
            'severity_operations' => $this->faker->randomElement(['S1', 'S2', 'S3', 'S4']),
            'severity_work_conditions' => $this->faker->randomElement(['S1', 'S2', 'S3', 'S4']),
            'severity_environment' => $this->faker->randomElement(['S1', 'S2', 'S3', 'S4']),
            'severity_image' => $this->faker->imageUrl(),
            'building_id' => Building::pluck('id')->random(),
        ];
    }
}
