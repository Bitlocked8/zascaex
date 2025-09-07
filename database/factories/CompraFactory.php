<?php

namespace Database\Factories;

use App\Models\Personal; // Importa el modelo relacionado
use App\Models\Proveedor; // Importa el modelo relacionado
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Compra>
 */
class CompraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'), // Fecha de compra dentro del último año
            'observaciones' => $this->faker->optional()->sentence, // Observaciones opcionales
            'proveedor_id' => Proveedor::get()->random()->id, // Llave foránea con proveedor
            'personal_id' => Personal::get()->random()->id, // Llave foránea con personal
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
