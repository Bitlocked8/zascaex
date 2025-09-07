<?php

namespace Database\Factories;

use App\Models\Coche; // Importa el modelo relacionado
use App\Models\Personal; // Importa el modelo relacionado
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asignacion>
 */
class AsignacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaInicio = $this->faker->dateTimeBetween('-1 year', 'now'); // Fecha de inicio aleatoria dentro del último año
        $fechaFinal = $this->faker->dateTimeBetween($fechaInicio, '+1 month'); // Fecha final dentro de un mes posterior a la fecha de inicio
        return [
            'fechaInicio' => $fechaInicio->format('Y-m-d'),
            'fechaFinal' => $fechaFinal->format('Y-m-d'),
            'estado' => $this->faker->boolean(80), // 80% probabilidad de ser activo
            'coche_id' => Coche::get()->random()->id, // Llave foránea con Coche
            'personal_id' => Personal::get()->random()->id, // Llave foránea con Personal
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
