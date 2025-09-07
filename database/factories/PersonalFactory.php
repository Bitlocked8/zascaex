<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Personal>
 */
class PersonalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombres' => $this->faker->firstName, // Genera un nombre aleatorio
            'apellidos' => $this->faker->lastName, // Genera un apellido aleatorio
            'direccion' => $this->faker->address, // Genera una dirección aleatoria
            'celular' => $this->faker->unique()->numerify('###########'), // Genera un número de celular único
            'estado' => $this->faker->boolean(80), // 80% de probabilidad de ser activo (1)
            'user_id' => User::get()->random()->id, // Relación con usuarios (puede ser null si no hay usuarios)
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
