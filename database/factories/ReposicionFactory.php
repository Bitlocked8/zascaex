<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            //
            'fecha' => $this->faker->date(), // Fecha aleatoria
            'cantidad' => $this->faker->numberBetween(1, 500), // Cantidad aleatoria entre 1 y 500
            'base_id' => \App\Models\Base::get()->random()->id, // Genera una base relacionada
            'personal_id' => \App\Models\Personal::get()->random()->id, // Genera un personal relacionado
        ];
    }
}
