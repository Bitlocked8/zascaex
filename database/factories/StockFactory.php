<?php

namespace Database\Factories;
use App\Models\Sucursal;
use App\Models\Producto; // Importa el modelo relacionado
use App\Models\Etiqueta; // Importa el modelo relacionad
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fechaElaboracion' => $this->faker->date,
            'fechaVencimiento' => $this->faker->date,
            'observaciones' => $this->faker->optional()->sentence,
            'producto_id' => Producto::inRandomOrder()->first()?->id,
            'etiqueta_id' => Etiqueta::inRandomOrder()->first()?->id,
            // 'sucursal_id' => Sucursal::inRandomOrder()->first()?->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
