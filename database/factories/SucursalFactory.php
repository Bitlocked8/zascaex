<?php

namespace Database\Factories;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sucursal>
 */
class SucursalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company . ' Sucursal', // Nombre de la sucursal
            'direccion' => $this->faker->address, // Dirección aleatoria
            'telefono' => $this->faker->numerify('###########'), // Número de contacto
            'zona' => $this->faker->optional()->citySuffix, // Zona opcional
            'empresa_id' => Empresa::get()->random()->id, // Llave foránea (empresa)
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
