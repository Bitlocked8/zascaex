<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tapa;
use App\Models\Existencia;

class TapaSeeder extends Seeder
{

    public function run(): void
    {
        $tapas = [
            ['imagen' => null, 'descripcion' => 'normal - transparente', 'color' => 'transparente', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => 'normal - blanco', 'color' => 'blanco', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => 'normal - verde', 'color' => 'verde', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => 'normal - rosado', 'color' => 'rosado', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => 'normal - rojo', 'color' => 'rojo', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => 'normal - azul', 'color' => 'azul', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => 'normal - negro', 'color' => 'negro', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => 'deportivas - rojo', 'color' => 'rojo', 'tipo' => 'deportivas'],
            ['imagen' => null, 'descripcion' => 'deportivas - azul', 'color' => 'azul', 'tipo' => 'deportivas'],
            ['imagen' => null, 'descripcion' => 'deportivas - negro', 'color' => 'negro', 'tipo' => 'deportivas'],
            ['imagen' => null, 'descripcion' => 'deportivas - transparente', 'color' => 'transparente', 'tipo' => 'deportivas'],
            ['imagen' => null, 'descripcion' => 'deportivas - blanco', 'color' => 'blanco', 'tipo' => 'deportivas'],
            ['imagen' => null, 'descripcion' => 'push up - azul', 'color' => 'azul', 'tipo' => 'push up'],
            ['imagen' => null, 'descripcion' => 'push up - rojo', 'color' => 'rojo', 'tipo' => 'push up'],
            ['imagen' => null, 'descripcion' => 'rosca - azul', 'color' => 'azul', 'tipo' => 'rosca'],
            ['imagen' => null, 'descripcion' => 'rosca - blanco', 'color' => 'blanco', 'tipo' => 'rosca'],
            ['imagen' => null, 'descripcion' => 'rosca - negro', 'color' => 'negro', 'tipo' => 'rosca'],
            ['imagen' => null, 'descripcion' => 'cuello largo - transparente', 'color' => 'transparente', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => 'cuello largo - blanco', 'color' => 'blanco', 'tipo' => 'normal'],
            ['imagen' => null, 'descripcion' => 'cuello largo - negro', 'color' => 'negro', 'tipo' => 'normal'],
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
