<?php

namespace Database\Factories;

use App\Models\Existencia;
use App\Models\Stock; // Importa el modelo relacionado
use App\Models\Venta; // Importa el modelo relacionado

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Itemventa>
 */
class ItemventaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cantidad' => $this->faker->numberBetween(1, 10),
            'precio' => $this->faker->randomFloat(2, 5, 50),
            'existencia_id' => Existencia::where('existenciable_type', 'App\\Models\\Stock')->inRandomOrder()->first()->id, // Solo existencias de Stock
            'venta_id' => Venta::inRandomOrder()->first()->id,
            'estado' => $this->faker->randomElement([1, 2]), // 1: Activo, 2: Inactivo
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
