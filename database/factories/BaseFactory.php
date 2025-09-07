<?php

namespace Database\Factories;

use App\Models\Elaboracion;
use App\Models\Preforma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base>
 */
class BaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'capacidad' => $this->faker->numberBetween(500, 2000),
            'estado' => $this->faker->boolean,
            'observaciones' => $this->faker->optional()->sentence,
            'preforma_id' => Preforma::get()->random()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
