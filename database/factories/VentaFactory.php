<?php

namespace Database\Factories;
use App\Models\Cliente; // Importa el modelo relacionado
use App\Models\Distribucion;
use App\Models\Personal;
use App\Models\Sucursal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Venta>
 */
class VentaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fechaPedido' => $this->faker->date('Y-m-d', 'now'), // Fecha aleatoria hasta hoy
            'fechaEntrega' => $this->faker->optional()->date('Y-m-d', '+1 month'), // Opcional: dentro del próximo mes
            'fechaMaxima' => $this->faker->optional()->date('Y-m-d', '+2 months'), // Opcional: dentro de los próximos dos meses
            'sucursal_id' => Sucursal::get()->random()->id, // Relación con Sucursal
            'cliente_id' => Cliente::get()->random()->id, // Relación con Cliente
            'estadoPedido' => $this->faker->randomElement([1, 2, 0]), // Estado aleatorio (1: pedido, 2: vendido, 3: cancelado)
            'estadoPago' => $this->faker->randomElement([0, 1]), // Pago completo (1) o parcial (0)
            'personal_id' => $this->faker->boolean(70) ? Personal::get()->random()->id : null, // 70% de probabilidad de tener personal
            'personalEntrega_id' => $this->faker->boolean(30) ? Distribucion::get()->random()->id : null, // 30% de probabilidad de tener distribución
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
