<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Personal;
use App\Models\Trabajo;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PersonalSeeder extends Seeder
{
    public function run(): void
    {

        $personalesSucursal1 = [
            4 => ['Tatiana Paola','Bruno','Abraham','Anais','Raquel','David','Kevin','Jaime','Andrés','Luis','Roberto'],
            3 => ['Walter','Waldo'],
            2 => ['Enrique','María'],
            1 => ['Osvaldo Martinez','Mónica Quintanilla','Ricardo Martinez'],
        ];

        $sucursal1_id = 1;
        foreach ($personalesSucursal1 as $rol_id => $nombresBloque) {
            foreach ($nombresBloque as $nombreCompleto) {
                $partes = explode(' ', $nombreCompleto, 2);
                $nombre = $partes[0];
                $apellido = $partes[1] ?? '';

                $email = strtolower(str_replace(' ', '.', $nombreCompleto)) . '@mail.com';

                $user = User::create([
                    'email' => $email,
                    'password' => Hash::make('trabajadorverzasca2025'),
                    'rol_id' => $rol_id,
                    'estado' => 1,
                ]);

                $personal = Personal::create([
                    'nombres' => $nombre,
                    'apellidos' => $apellido,
                    'celular' => '7' . rand(1000000, 9999999),
                    'user_id' => $user->id,
                    'estado' => 1,
                    'direccion' => null,
                ]);

                Trabajo::create([
                    'fechaInicio' => Carbon::today(),
                    'estado' => 1,
                    'sucursal_id' => $sucursal1_id,
                    'personal_id' => $personal->id,
                    'labor_id' => null,
                ]);
            }
        }

        $personalesSucursal2 = [
            'Rosario Rivera Torres',
            'Lucia Leidi Lozano Roja',
            'Roger Ambrocio Rios',
            'Jhon Deivi Soria Rodriguez',
            'Danitza Mejia Marin',
        ];

        $sucursal2_id = 2;
        $rolSucursal2 = 2;
        foreach ($personalesSucursal2 as $nombreCompleto) {
            $partes = explode(' ', $nombreCompleto, 2);
            $nombre = $partes[0];
            $apellido = $partes[1] ?? '';

            $email = strtolower(str_replace(' ', '.', $nombreCompleto)) . '@mail.com';

            $user = User::create([
                'email' => $email,
                'password' => Hash::make('trabajadorverzasca2025'),
                'rol_id' => $rolSucursal2,
                'estado' => 1,
            ]);

            $personal = Personal::create([
                'nombres' => $nombre,
                'apellidos' => $apellido,
                'celular' => '7' . rand(1000000, 9999999),
                'user_id' => $user->id,
                'estado' => 1,
                'direccion' => null,
            ]);

            Trabajo::create([
                'fechaInicio' => Carbon::today(),
                'estado' => 1,
                'sucursal_id' => $sucursal2_id,
                'personal_id' => $personal->id,
                'labor_id' => null,
            ]);
        }
    }
}
