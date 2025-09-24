<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Preforma>
 */
class PreformaFactory extends Factory
{
    protected $model = \App\Models\Preforma::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imagen' => $this->faker->optional()->imageUrl(200, 200),
            'descripcion' => $this->faker->optional()->sentence,
            'estado' => $this->faker->boolean,
            'observaciones' => $this->faker->optional()->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
