<?php

namespace Database\Factories;

use App\Models\Distribucion;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Itemdistribucion>
 */
class ItemdistribucionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'cantidadNuevo' => $this->faker->numberBetween(0, 100), // Cantidad de productos nuevos
            'cantidadUsados' => $this->faker->numberBetween(0, 100), // Cantidad de productos usados
            'stock_id' => Stock::get()->random()->id, // Relación con stock
            'distribucion_id' => Distribucion::get()->random()->id, // Relación con distribución
        ];
    }
}
