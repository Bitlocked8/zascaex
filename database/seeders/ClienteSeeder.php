<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            [
                'codigo' => 'CLI-001',
                'nombre' => '360 Snack',
                'empresa' => null,
                'nitCi' => '12345678',
                'razonSocial' => null,
                'celular' => '76944896',
                'telefono' => null,
                'correo' => '360_snack@example.com',
                'categoria' => 1,
                'latitud' => -17.376004,
                'longitud' => -66.178282,
                'foto' => null,
                'estado' => 1,
                'verificado' => 1,
            ],
            [
                'codigo' => 'CLI-002',
                'nombre' => 'Abb Clínic Botellitas',
                'empresa' => null,
                'nitCi' => '98765432',
                'razonSocial' => null,
                'celular' => '62612007',
                'telefono' => null,
                'correo' => 'abb_clinic_botellitas@example.com',
                'categoria' => 1,
                'latitud' => -17.371147,
                'longitud' => -66.169822,
                'foto' => null,
                'estado' => 1,
                'verificado' => 1,
            ],
            [
                'codigo' => 'CLI-003',
                'nombre' => 'Snack Center',
                'empresa' => 'Snack Center SRL',
                'nitCi' => '54321678',
                'razonSocial' => 'Snack Center SRL',
                'celular' => '70112233',
                'telefono' => '44112233',
                'correo' => 'snack_center@example.com',
                'categoria' => 2,
                'latitud' => -17.390000,
                'longitud' => -66.180000,
                'foto' => null,
                'estado' => 1,
                'verificado' => 1,
            ],
            [
                'codigo' => 'CLI-004',
                'nombre' => 'Delicias Bolivianas',
                'empresa' => 'Delicias SRL',
                'nitCi' => '87654321',
                'razonSocial' => 'Delicias SRL',
                'celular' => '78451236',
                'telefono' => '44551236',
                'correo' => 'delicias_bolivianas@example.com',
                'categoria' => 2,
                'latitud' => -17.398000,
                'longitud' => -66.160000,
                'foto' => null,
                'estado' => 1,
                'verificado' => 1,
            ],
        ];

        foreach ($clientes as $data) {
            // Crear usuario vinculado al cliente
            $user = User::create([
                'name' => $data['nombre'],
                'email' => $data['correo'],
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);

            // Crear cliente asociado al usuario
            Cliente::create(array_merge($data, [
                'user_id' => $user->id,
            ]));
        }
    }
}