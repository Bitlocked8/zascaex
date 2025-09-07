<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coche>
 */
class CocheFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movil' => $this->faker->numberBetween(1,20), // Genera una marca aleatoria
            'marca' => $this->faker->company, // Genera una marca aleatoria
            'modelo' => $this->faker->word, // Genera un modelo aleatorio
            'anio' => $this->faker->year, // Genera un año aleatorio
            'color' => $this->faker->safeColorName, // Genera un nombre de color seguro
            'placa' => $this->faker->unique()->regexify('[A-Z]{3}-[0-9]{3}'), // Genera una placa única
            'estado' => $this->faker->boolean(80), // 80% de probabilidad de ser activo
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
