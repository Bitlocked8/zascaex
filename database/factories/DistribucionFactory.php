<?php

namespace Database\Factories;
use App\Models\Asignacion; // Importa el modelo relacionado
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Distribucion>
 */
class DistribucionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'), // Fecha aleatoria en el último año
            // 'producto' => $this->faker->word, // Nombre del producto
            // 'presentado' => $this->faker->randomElement(['Caja', 'Botella', 'Paquete']), // Forma de presentación
            // 'cantidad' => $this->faker->numberBetween(1, 500), // Cantidad aleatoria
            // 'precio' => $this->faker->randomFloat(2, 10, 1000), // Precio aleatorio entre 10 y 1000
            // 'empresa' => $this->faker->company, // Nombre de la empresa involucrada
            // 'cliente' => $this->faker->name, // Nombre del cliente
            // 'pago' => $this->faker->randomElement(['QR', 'Contado', 'Crédito']), // Tipo de pago
            // 'pedido' => $this->faker->uuid, // Pedido asociado generado como UUID
            // 'encargado' => $this->faker->name, // Nombre del encargado            
            'estado' => $this->faker->numberBetween(1,3), // Observaciones opcionales
            'observaciones' => $this->faker->optional()->sentence, // Observaciones opcionales
            'asignacion_id' => Asignacion::get()->random()->id, // Llave foránea con Asignación
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
