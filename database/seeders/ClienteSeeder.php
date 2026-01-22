<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Personal;
use Illuminate\Support\Str;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = require database_path('data/clientes.php');

        $personales = Personal::whereHas(
            'user',
            fn($q) =>
            $q->where('rol_id', 3)
        )->pluck('id');

        if ($personales->isEmpty()) {
            $this->command->warn('No existen personales con rol distribuidor (rol_id = 3)');
            return;
        }

        foreach ($clientes as $cliente) {

            $nombreUsuario = strtolower(str_replace(' ', '', $cliente['nombre']));
            $codigo = $nombreUsuario . rand(1000, 9999) . Str::lower(Str::random(1));

            $user = User::firstOrCreate(
                ['email' => $codigo],
                [
                    'password' => bcrypt('verzasca1234'),
                    'rol_id' => 5,
                    'estado' => 1
                ]
            );

            if (Cliente::where('user_id', $user->id)->exists()) {
                continue;
            }

            if (!empty($cliente['ubicacion'])) {
                [$lat, $lng] = array_pad(explode(',', $cliente['ubicacion']), 2, null);
                $cliente['latitud'] = trim($lat);
                $cliente['longitud'] = trim($lng);
            } else {
                $cliente['latitud'] = null;
                $cliente['longitud'] = null;
            }

            Cliente::create([
                ...$cliente,
                'user_id' => $user->id,
                'personal_id' => $personales->random(),
                'fijar_personal' => 0,
                'sucursal_id' => 1,
            ]);
        }
    }
}
