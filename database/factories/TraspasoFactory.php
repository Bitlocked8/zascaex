<?php

namespace Database\Factories;

use App\Models\Traspaso;
use App\Models\Existencia;
use App\Models\Personal;
use Illuminate\Database\Eloquent\Factories\Factory;

class TraspasoFactory extends Factory
{
    protected $model = Traspaso::class;

    public function definition(): array
    {
        // Obtener existencia origen con sucursal definida
        $existenciaOrigen = Existencia::inRandomOrder()->first() ?? Existencia::factory()->create();

        // Clonar datos para crear existencia destino con misma referencia pero sucursal diferente
        $existenciaDestino = Existencia::where('existenciable_type', $existenciaOrigen->existenciable_type)
            ->where('existenciable_id', $existenciaOrigen->existenciable_id)
            ->where('sucursal_id', '!=', $existenciaOrigen->sucursal_id)
            ->first();

        if (!$existenciaDestino) {
            $existenciaDestino = Existencia::factory()->create([
                'existenciable_type' => $existenciaOrigen->existenciable_type,
                'existenciable_id' => $existenciaOrigen->existenciable_id,
                'sucursal_id' => fake()->randomElement([1, 2, 3]), // ajusta según sucursales reales
                'cantidad' => 0,
            ]);
        }

        return [
            'existencia_origen_id' => $existenciaOrigen->id,
            'existencia_destino_id' => $existenciaDestino->id,
            'personal_id' => Personal::inRandomOrder()->first()->id ?? Personal::factory()->create()->id,
            'cantidad' => $this->faker->numberBetween(1, min(50, $existenciaOrigen->cantidad ?? 100)),
            'fecha_traspaso' => $this->faker->date(),
            'observaciones' => $this->faker->optional()->sentence,
        ];
    }
}
