<?php

namespace Database\Factories;

use App\Models\Venta; // Importa el modelo relacionado
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pagoventa>
 */
class PagoventaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tiposDePago = ['QR', 'contado', 'crédito']; // Opciones de tipo de pago
        return [
            'tipo' => $this->faker->randomElement($tiposDePago), // Selecciona un tipo de pago aleatorio
            'codigo' => $this->faker->optional()->uuid, // Genera un código único opcional para crédito
            'monto' => $this->faker->optional()->uuid, // Genera un código único opcional para crédito
            'fechaPago' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'), // Fecha de pago en el último año
            'observaciones' => $this->faker->optional()->sentence, // Observaciones opcionales
            'venta_id' => Venta::get()->random()->id, // Llave foránea con Venta
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
