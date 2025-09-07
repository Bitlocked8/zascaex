<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Etiqueta>
 */
class EtiquetaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'capacidad' => $this->faker->numberBetween(500, 2000) . 'ml',
            'estado' => $this->faker->boolean,
            'cliente_id' => Cliente::get()->random()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
