<?php

namespace Database\Factories;

use App\Models\Base;
use App\Models\Etiqueta;
use App\Models\Preforma;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\Sucursal;
use App\Models\Tapa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Existencia>
 */
class ExistenciaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'existenciable_id' => function () {
            //     $modelos = [Preforma::class, Base::class, Tapa::class, Etiqueta::class, Producto::class, Stock::class]; // Modelos posibles
            //     $modeloSeleccionado = $modelos[array_rand($modelos)]; // Selecciona aleatoriamente un modelo
            //     return $modeloSeleccionado::get()->random()->id;
            // },
            // 'existenciable_type' => function () use ($modelos) {
            //     return $modelos[array_rand($modelos)]; // Selecciona aleatoriamente el modelo correspondiente
            // },
            // 'cantidad' => $this->faker->numberBetween(10, 100),
            // 'sucursal_id' => Sucursal::get()->random()->id,
            // 'created_at' => now(),
            // 'updated_at' => now(),
        ];
    }
}
