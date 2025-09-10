<?php

namespace Database\Factories;

use App\Models\Promo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promo>
 */
class PromoFactory extends Factory
{
    protected $model = Promo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre'          => $this->faker->words(3, true), // nombre de la promo
            'tipo_descuento'  => $this->faker->randomElement(['porcentaje', 'monto']), // tipo de descuento
            'valor_descuento' => $this->faker->randomFloat(2, 5, 50), // valor del descuento
            'fecha_inicio'    => now(), // fecha de inicio hoy
            'fecha_fin'       => now()->addDays($this->faker->numberBetween(7, 30)), // fecha de fin aleatoria
            'activo'          => $this->faker->boolean(80), // 80% de probabilidad de estar activo
        ];
    }
}
