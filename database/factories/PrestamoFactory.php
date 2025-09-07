<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prestamo>
 */
class PrestamoFactory extends Factory
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
            'articulo' => $this->faker->randomElement(['Botellones', 'Dispenser normal', 'Dispenser electrónico']),
            'cantidad' => $this->faker->numberBetween(1, 100),
            'estado' => $this->faker->randomElement([1, 2]), // 1: Debe, 2: Devolvió
            'garantia' => $this->faker->optional()->numberBetween(50, 500), // Garantía opcional
            'observaciones' => $this->faker->optional()->sentence(), // Observaciones opcionales
            'nroContrato' => $this->faker->optional()->numberBetween(1000, 9999), // Número de contrato opcional
            'cliente_id' => \App\Models\Cliente::get()->random()->id, // Genera un cliente relacionado
            'prestador' => $this->faker->optional()->randomElement(\App\Models\Personal::pluck('id')->toArray()), // Prestador opcional
            'recuperador' => $this->faker->optional()->randomElement(\App\Models\Personal::pluck('id')->toArray()), // Recuperador opcional
        ];
    }
}
