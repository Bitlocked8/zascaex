<?php

namespace Database\Factories;

use App\Models\Base; // Importa el modelo relacionado
use App\Models\Existencia;
use App\Models\Personal; // Importa el modelo relacionado
use App\Models\Producto;
use App\Models\Tapa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Embotellado>
 */
class EmbotelladoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'existencia_base_id' => Existencia::whereHasMorph('existenciable', [Base::class])->get()->random()->id, // Bases como entrada
            'existencia_tapa_id' => Existencia::whereHasMorph('existenciable', [Tapa::class])->get()->random()->id, // Tapas como entrada
            'existencia_producto_id' => Existencia::whereHasMorph('existenciable', [Producto::class])->get()->random()->id, // Producto generado
            'personal_id' => Personal::get()->random()->id,
            'cantidad_base_usada' => $this->faker->numberBetween(10, 50),
            'cantidad_tapa_usada' => $this->faker->numberBetween(10, 50),
            'cantidad_generada' => $this->faker->numberBetween(10, 50),
            'fecha_embotellado' => $this->faker->date,
            'observaciones' => $this->faker->optional()->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
