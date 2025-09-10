<?php

namespace Database\Factories;

use App\Models\ItemPromo;
use App\Models\Cliente;
use App\Models\Promo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemPromo>
 */
class ItemPromoFactory extends Factory
{
    protected $model = ItemPromo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Vinculamos con un cliente y una promo existentes o nuevas
            'cliente_id'       => Cliente::factory(),
            'promo_id'         => Promo::factory(),
            'usos_realizados'  => 0,
            'uso_maximo'       => $this->faker->numberBetween(1, 10),
            'estado'           => $this->faker->randomElement(['activo', 'usado', 'expirado']),
            'fecha_asignada'   => now(),
            'fecha_expiracion' => now()->addDays($this->faker->numberBetween(7, 30)),
        ];
    }
}
