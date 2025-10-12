<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tapa;
use App\Models\Existencia;

class TapaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tapas = [
            ['imagen' => null, 'descripcion' => '', 'color' => 'transparente', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'blanco', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'verde', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'rosado', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'rojo', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'azul', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'negro', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'rojo', 'tipo' => 'deportivas'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'azul', 'tipo' => 'deportivas'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'negro', 'tipo' => 'deportivas'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'transparente', 'tipo' => 'deportivas'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'blanco', 'tipo' => 'deportivas'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'azul', 'tipo' => 'push up'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'rojo', 'tipo' => 'push up'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'azul', 'tipo' => 'rosca'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'blanco', 'tipo' => 'rosca'],
            ['imagen' => null, 'descripcion' => '', 'color' => 'negro', 'tipo' => 'rosca'],
            ['imagen' => null, 'descripcion' => 'cuello largo', 'color' => 'transparente', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => 'cuello largo', 'color' => 'blanco', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => 'cuello largo', 'color' => 'negro', 'tipo' => 'normal'],
        ];

       foreach ($tapas as $data) {
            foreach ([1, 2] as $sucursal_id) {
                $tapa = Tapa::create([
                    'imagen' => null,
                    'descripcion' => $data['descripcion'],
                    'color' => $data['color'],
                    'tipo' => $data['tipo'],
                    'estado' => 1,
                ]);
                Existencia::create([
                    'existenciable_type' => Tapa::class,
                    'existenciable_id' => $tapa->id,
                    'cantidad' => 0,
                    'cantidadMinima' => 0,
                    'sucursal_id' => $sucursal_id,
                ]);
            }
        }
    }
}
