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
            'existencia_entrada_id' => Existencia::whereHasMorph('existenciable', [Preforma::class])->get()->random()->id,
            'existencia_salida_id' => Existencia::whereHasMorph('existenciable', [Base::class])->get()->random()->id,
            'personal_id' => Personal::get()->random()->id,
            'cantidad_entrada' => $this->faker->numberBetween(10, 50),
            'cantidad_salida' => $this->faker->numberBetween(5, 50),
            'merma' => $this->faker->numberBetween(0, 5),
            'fecha_elaboracion' => $this->faker->date,
            'observaciones' => $this->faker->optional()->sentence,
            'codigo' => 'L-' . $this->faker->unique()->numberBetween(1000, 9999), // código de lote
            'estado' => 'pendiente', // estado inicial
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
