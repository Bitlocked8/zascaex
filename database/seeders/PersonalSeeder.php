<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Personal;
use App\Models\Trabajo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PersonalSeeder extends Seeder
{
    public function run(): void
    {
        $personalesSucursal1 = [
            4 => ['Tatiana Paola', 'Bruno', 'Abraham', 'Anais', 'Raquel', 'Kevin', 'Andrés', 'Luis', 'Roberto'],
            3 => ['Walter', 'Waldo'],
            2 => ['Enrique', 'María', 'David', 'Jaime'],
            1 => ['Osvaldo Martinez', 'Mónica Quintanilla', 'Ricardo Martinez'],
        ];

        $sucursal1_id = 1;

        foreach ($personalesSucursal1 as $rol_id => $nombresBloque) {
            foreach ($nombresBloque as $nombreCompleto) {
                $this->crearPersonal($nombreCompleto, $rol_id, $sucursal1_id);
            }
        }

        $personalesSucursal2 = [
            ['nombres' => 'Rosario', 'apellidos' => 'Rivera Torres', 'direccion' => '8vo anillo cambodromo', 'celular' => '61336551'],
            ['nombres' => 'Lucia Leidi', 'apellidos' => 'Lozano Roja', 'direccion' => 'Av. beni 8vo anillo C/ sagrado corazon', 'celular' => '64601844'],
            ['nombres' => 'Roger', 'apellidos' => 'Ambrocio Rios', 'direccion' => 'Plan 3mil Av/ paurito', 'celular' => '63377975'],
            ['nombres' => 'Jhon Deivi', 'apellidos' => 'Soria Rodriguez', 'direccion' => 'Av. Beni C/ Paraiso N°8032', 'celular' => '77315104'],
            ['nombres' => 'Danitza', 'apellidos' => 'Mejia Marin', 'direccion' => '8vo anilo Cambodromo C/ bibosi N° 83', 'celular' => '78502161'],
        ];

        $sucursal2_id = 2;
        $rolSucursal2 = 2;

        foreach ($personalesSucursal2 as $data) {
            $this->crearPersonalConDatos($data['nombres'], $data['apellidos'], $data['direccion'], $data['celular'], $rolSucursal2, $sucursal2_id);
        }
    }

    private function crearPersonal(string $nombreCompleto, int $rol_id, int $sucursal_id)
    {
        $partes = explode(' ', $nombreCompleto, 2);
        $nombre = $partes[0];
        $apellido = $partes[1] ?? '';

        $codigo = strtolower(str_replace(' ', '', $nombreCompleto)) . rand(1000, 9999) . Str::lower(Str::random(1));

        $user = User::create([
            'email' => $codigo,
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
            'sucursal_id' => $sucursal_id,
            'personal_id' => $personal->id,
            'labor_id' => null,
        ]);
    }

    private function crearPersonalConDatos(string $nombre, string $apellidos, string $direccion, string $celular, int $rol_id, int $sucursal_id)
    {
        $codigo = strtolower(str_replace(' ', '', $nombre . $apellidos)) . rand(1000, 9999) . Str::lower(Str::random(1));

        $user = User::create([
            'email' => $codigo,
            'password' => Hash::make('trabajadorverzasca2025'),
            'rol_id' => $rol_id,
            'estado' => 1,
        ]);

        $personal = Personal::create([
            'nombres' => $nombre,
            'apellidos' => $apellidos,
            'celular' => $celular,
            'user_id' => $user->id,
            'estado' => 1,
            'direccion' => $direccion,
        ]);

        Trabajo::create([
            'fechaInicio' => Carbon::today(),
            'estado' => 1,
            'sucursal_id' => $sucursal_id,
            'personal_id' => $personal->id,
            'labor_id' => null,
        ]);
    }
}
