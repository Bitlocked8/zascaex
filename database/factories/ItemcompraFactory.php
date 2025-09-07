<?php

namespace Database\Factories;

use App\Models\Compra;
use App\Models\Existencia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Itemcompra>
 */
class ItemcompraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cantidad' => $this->faker->numberBetween(10, 100),
            'precio' => $this->faker->randomFloat(2, 1, 50),
            'existencia_id' => Existencia::get()->random()->id,
            'compra_id' => Compra::get()->random()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
