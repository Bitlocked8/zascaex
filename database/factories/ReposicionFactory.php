<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Existencia;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reposicion>
 */
class ReposicionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo' => 'R-' . now()->format('Ymd') . '-' . str_pad($this->faker->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'fecha' => $this->faker->date(),
            'cantidad' => $this->faker->numberBetween(1, 500),
            'existencia_id' => Existencia::inRandomOrder()->first()->id,
            'personal_id' => \App\Models\Personal::inRandomOrder()->first()->id,
            'proveedor_id' => \App\Models\Proveedor::inRandomOrder()->first()->id,
            'observaciones' => $this->faker->optional()->sentence(),
        ];
    }
}
