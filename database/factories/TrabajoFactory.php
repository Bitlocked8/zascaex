<?php

namespace Database\Factories;

use App\Models\Personal;
use App\Models\Sucursal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trabajo>
 */
class TrabajoFactory extends Factory
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
            'fechaInicio' => $this->faker->date(),
            'fechaFinal' => $this->faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
            'estado' => $this->faker->boolean(80), // 80% de probabilidad de ser activo
            'sucursal_id' => Sucursal::get()->random()->id,
            'personal_id' => Personal::get()->random()->id,
        ];
    }
}
