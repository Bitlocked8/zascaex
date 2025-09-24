<?php

namespace Database\Factories;

use App\Models\Base;
use App\Models\Existencia;
use App\Models\Personal;
use App\Models\Producto;
use App\Models\Tapa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Embotellado>
 */
class EmbotelladoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Elegir una sucursal al azar
        $sucursalId = \App\Models\Sucursal::inRandomOrder()->first()->id;

        // Tomar insumos de la misma sucursal
        $base = Existencia::whereHasMorph('existenciable', [Base::class])
            ->where('sucursal_id', $sucursalId)
            ->inRandomOrder()
            ->first();

        $tapa = Existencia::whereHasMorph('existenciable', [Tapa::class])
            ->where('sucursal_id', $sucursalId)
            ->inRandomOrder()
            ->first();

        $producto = Existencia::whereHasMorph('existenciable', [Producto::class])
            ->where('sucursal_id', $sucursalId)
            ->inRandomOrder()
            ->first();

        return [
            'existencia_base_id' => $base?->id,
            'existencia_tapa_id' => $tapa?->id,
            'existencia_producto_id' => $producto?->id,
            'personal_id' => Personal::inRandomOrder()->first()->id,
            'cantidad_base_usada' => $this->faker->numberBetween(10, 50),
            'cantidad_tapa_usada' => $this->faker->numberBetween(10, 50),
            'cantidad_generada' => $this->faker->numberBetween(10, 50),
            'fecha_embotellado' => $this->faker->date,
            'observaciones' => $this->faker->optional()->sentence,
            'codigo' => 'E-' . $this->faker->unique()->numberBetween(1000, 9999),
            'estado' => $this->faker->randomElement(['pendiente', 'terminado']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
