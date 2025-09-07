<?php

namespace Database\Factories;

use App\Models\Base;
use App\Models\Enbotellado; // Importa el modelo relacionado
use App\Models\Tapa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word,
            'tipoContenido' => $this->faker->numberBetween(1, 5),
            'tipoProducto' => $this->faker->boolean,
            'capacidad' => $this->faker->numberBetween(500, 2000),
            'unidad' => 'ml',
            'precioReferencia' => $this->faker->randomFloat(2, 1, 100),
            'precioReferencia2' => $this->faker->optional()->randomFloat(2, 1, 100),
            'precioReferencia3' => $this->faker->optional()->randomFloat(2, 1, 100),
            'observaciones' => $this->faker->optional()->sentence,
            'estado' => $this->faker->boolean,
            'base_id' => Base::inRandomOrder()->first()?->id,
            'tapa_id' => Tapa::inRandomOrder()->first()?->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
