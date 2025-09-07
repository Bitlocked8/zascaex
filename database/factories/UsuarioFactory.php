<?php

namespace Database\Factories;

use App\Models\Rol;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'login' => $this->faker->unique()->userName, // Nombre de usuario único
            'password' => Hash::make(12345678), // Contraseña encriptada
            'estado' => $this->faker->boolean(80), // 80% de probabilidad de que sea activo (1)
            'rol_id' => Rol::get()->random()->id, // Crea un rol asociado o asigna un existente
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
