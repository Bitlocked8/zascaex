<?php

namespace Database\Factories;

use App\Models\Etiqueta;
use App\Models\Existencia;
use App\Models\Personal;
use App\Models\Producto;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Etiquetado>
 */
class EtiquetadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'existencia_producto_id' => Existencia::whereHasMorph('existenciable', [Producto::class])->get()->random()->id, // Producto como entrada
            'existencia_etiqueta_id' => Existencia::whereHasMorph('existenciable', [Etiqueta::class])->get()->random()->id, // Etiqueta como entrada
            'existencia_stock_id' => Existencia::whereHasMorph('existenciable', [Stock::class])->get()->random()->id, // Stock generado
            'personal_id' => Personal::get()->random()->id,
            'cantidad_producto_usado' => $this->faker->numberBetween(10, 50),
            'cantidad_etiqueta_usada' => $this->faker->numberBetween(10, 50),
            'cantidad_generada' => $this->faker->numberBetween(10, 50),
            'fecha_etiquetado' => $this->faker->date,
            'observaciones' => $this->faker->optional()->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
