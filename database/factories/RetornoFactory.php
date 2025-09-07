<?php

namespace Database\Factories;
use App\Models\Distribucion; // Importa el modelo relacionado
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Retorno>
 */
class RetornoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'fechaIngreso' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'), // Fecha de ingreso aleatoria en el último año
            'botellonesNuevos' => $this->faker->numberBetween(0, 50), // Cantidad de productos nuevos
            // 'nuevos' => $this->faker->numberBetween(0, 50), // Cantidad de productos nuevos
            'llenos' => $this->faker->numberBetween(0, 50), // Cantidad de productos llenos
            'vacios' => $this->faker->numberBetween(0, 50), // Cantidad de productos vacíos
            'reportado' => $this->faker->numberBetween(0, 20), // Cantidad de productos rechazados
            'desechar' => $this->faker->numberBetween(0, 30), // Cantidad de productos recuperados
            'recuperados' => $this->faker->numberBetween(0, 30),
            'observaciones' => $this->faker->optional()->sentence, // Observaciones opcionales
            'distribucion_id' => Distribucion::get()->random()->id, // Llave foránea con Distribución
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
