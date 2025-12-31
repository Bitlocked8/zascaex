<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Personal;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = require database_path('data/clientes.php');
        $personales = Personal::whereHas('user', fn($q) => $q->where('rol_id', 3))->pluck('id');

        foreach ($clientes as $cliente) {
            $nombreUsuario = strtolower(str_replace(' ', '', $cliente['nombre']));
            $email = $nombreUsuario . '@mail.com';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'password' => bcrypt('verzasca1234'),
                    'rol_id' => 5,
                    'estado' => 1
                ]
            );

            if (!empty($cliente['ubicacion'])) {
                [$lat, $lng] = array_pad(explode(',', $cliente['ubicacion']), 2, null);
                $cliente['latitud'] = trim($lat);
                $cliente['longitud'] = trim($lng);
            } else {
                $cliente['latitud'] = null;
                $cliente['longitud'] = null;
            }
            $cliente['user_id'] = $user->id;
            $cliente['personal_id'] = $personales->random();
            $cliente['fijar_personal'] = 0;

            Cliente::create($cliente);
        }
    }
}
