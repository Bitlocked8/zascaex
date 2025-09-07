<?php

namespace Database\Factories;

use App\Models\Base;
use App\Models\Personal;
use App\Models\Existencia;
use App\Models\Preforma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Elaboracion>
 */
class ElaboracionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'existencia_entrada_id' => Existencia::whereHasMorph('existenciable', [Preforma::class])->get()->random()->id, // Preformas como entrada
            'existencia_salida_id' => Existencia::whereHasMorph('existenciable', [Base::class])->get()->random()->id, // Bases como salida
            'personal_id' => Personal::get()->random()->id,
            'cantidad_entrada' => $this->faker->numberBetween(10, 50),
            'cantidad_salida' => $this->faker->numberBetween(10, 50),
            'fecha_elaboracion' => $this->faker->date,
            'observaciones' => $this->faker->optional()->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
