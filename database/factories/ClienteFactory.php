<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * El modelo asociado a esta factory.
     *
     * @var string
     */
    protected $model = Cliente::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo' => 'CL-' . now()->format('Ymd') . '-' . str_pad($this->faker->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'nombre' => $this->faker->name,
            'empresa' => $this->faker->company,
            'nitCi' => $this->faker->unique()->numerify('########'),
            'razonSocial' => $this->faker->companySuffix . ' ' . $this->faker->company,
            'telefono' => $this->faker->numerify('###########'),
            'celular' => $this->faker->numerify('7########'),
            'correo' => $this->faker->unique()->safeEmail,
            'latitud' => $this->faker->latitude(-90, 90),
            'longitud' => $this->faker->longitude(-180, 180),
            'foto' => $this->faker->imageUrl(200, 200, 'people', true, 'Cliente'),
            'estado' => $this->faker->boolean(80),
            'verificado' => $this->faker->boolean(90),
            'user_id' => User::factory()->state([
                'rol_id' => 5,
                'password' => bcrypt('12345678'),
            ]),

        ];
    }
}
