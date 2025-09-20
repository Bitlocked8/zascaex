<?php

namespace Database\Factories;

use App\Models\Existencia;
use App\Models\Venta;
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
        $existencia = Existencia::where('existenciable_type', 'App\\Models\\Producto')
            ->inRandomOrder()
            ->first();

        return [
            'cantidad' => $this->faker->numberBetween(1, 10),
            'precio' => $this->faker->randomFloat(2, 5, 50),
            'existencia_id' => $existencia ? $existencia->id : Existencia::factory()->create([
                'existenciable_type' => 'App\\Models\\Producto',
                'existenciable_id' => \App\Models\Producto::factory()->create()->id,
            ])->id,
            'venta_id' => Venta::inRandomOrder()->first()?->id ?? Venta::factory()->create()->id,
            'estado' => $this->faker->randomElement([1, 2]), // 1: Activo, 2: Inactivo
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
