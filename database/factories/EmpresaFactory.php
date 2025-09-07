<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company, // Genera un nombre de empresa
            'slogan' => $this->faker->catchPhrase, // Genera un slogan (opcional)
            'mision' => $this->faker->paragraph, // Genera una misión (opcional)
            'vision' => $this->faker->paragraph, // Genera una visión (opcional)
            'nroContacto' => $this->faker->numerify('###########'), // Genera un número de contacto
            'facebook' => $this->faker->url, // Genera una URL para Facebook (opcional)
            'instagram' => $this->faker->url, // Genera una URL para Instagram (opcional)
            'tiktok' => $this->faker->url, // Genera una URL para TikTok (opcional)
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
