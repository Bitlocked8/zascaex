<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proveedor>
 */
class ProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'razonSocial' => $this->faker->company, // Nombre de la razón social
            'nombreContacto' => $this->faker->optional()->name, // Nombre de contacto opcional
            'direccion' => $this->faker->address, // Dirección aleatoria
            'telefono' => $this->faker->numerify('########'), // Número telefónico de 10 dígitos
            'correo' => $this->faker->unique()->safeEmail, // Correo electrónico único
            'tipo' => $this->faker->randomElement(['tapas', 'preformas', 'etiquetas']), // Tipo de proveedor
            'servicio' => $this->faker->randomElement(['soplado', 'transporte']), // Servicio ofrecido
            'descripcion' => $this->faker->sentence, // Descripción breve
            'precio' => $this->faker->randomFloat(2, 10, 1000), // Precio aleatorio entre 10 y 1000
            'tiempoEntrega' => $this->faker->randomElement(['1 día', '3 días', '1 semana']), // Tiempo de entrega
            'estado' => $this->faker->boolean(80), // Estado activo con 80% de probabilidad
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
