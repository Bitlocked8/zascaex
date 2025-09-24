<?php

namespace Database\Factories;

use App\Models\Base;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base>
 */
class BaseFactory extends Factory
{
    protected $model = Base::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'descripcion' => $this->faker->sentence(3),
            'capacidad' => $this->faker->numberBetween(500, 2000),
            'estado' => $this->faker->boolean,
            'observaciones' => $this->faker->optional()->sentence,
            'imagen' => $this->faker->optional()->imageUrl(200, 200),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
