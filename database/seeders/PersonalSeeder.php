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
                $this->crearPersonal($nombreCompleto, $rol_id, $sucursal1_id);
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
            $this->crearPersonal($nombreCompleto, $rolSucursal2, $sucursal2_id);
        }
    }

    /**
     * Función para crear personal + usuario + trabajo, evitando emails duplicados
     */
    private function crearPersonal(string $nombreCompleto, int $rol_id, int $sucursal_id)
    {
        $partes = explode(' ', $nombreCompleto, 2);
        $nombre = $partes[0];
        $apellido = $partes[1] ?? '';

        // Generar email base
        $emailBase = strtolower(str_replace(' ', '.', $nombreCompleto));
        $email = $emailBase . '@mail.com';

        // Evitar duplicados en users
        $contador = 1;
        while(User::where('email', $email)->exists()) {
            $email = $emailBase . $contador . '@mail.com';
            $contador++;
        }

        // Crear usuario
        $user = User::create([
            'email' => $email,
            'password' => Hash::make('trabajadorverzasca2025'),
            'rol_id' => $rol_id,
            'estado' => 1,
        ]);

        // Crear personal
        $personal = Personal::create([
            'nombres' => $nombre,
            'apellidos' => $apellido,
            'celular' => '7' . rand(1000000, 9999999),
            'user_id' => $user->id,
            'estado' => 1,
            'direccion' => null,
        ]);

        // Crear trabajo
        Trabajo::create([
            'fechaInicio' => Carbon::today(),
            'estado' => 1,
            'sucursal_id' => $sucursal_id,
            'personal_id' => $personal->id,
            'labor_id' => null,
        ]);
    }
}
