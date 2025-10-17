<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Personal;
use Illuminate\Support\Facades\Hash;

class PersonalSeeder extends Seeder
{
    public function run(): void
    {
        $personales = [
            4 => ['Tatiana','Paola','Bruno','Abraham','Anais','Raquel','David','Kevin','Jaime','Andrés','Luis','Roberto'],
            3 => ['Walter','Waldo'],
            2 => ['Enrique','María'],
            1 => ['Osvaldo Martinez','Mónica Quintanilla','Ricardo Martinez'],
        ];

        foreach ($personales as $rol_id => $nombresBloque) {
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

                Personal::create([
                    'nombres' => $nombre,
                    'apellidos' => $apellido,
                    'celular' => '7' . rand(1000000, 9999999),
                    'user_id' => $user->id,
                    'estado' => 1,
                    'direccion' => null,
                ]);
            }
        }
    }
}
