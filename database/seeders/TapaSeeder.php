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
            ['descripcion' => 'normal - blanco', 'color' => 'blanco', 'tipo' => 'normal', 'imagen' => 'tapas/blanca.jpg'],
            ['descripcion' => 'normal - azul', 'color' => 'azul', 'tipo' => 'normal', 'imagen' => 'tapas/azul.jpg'],
            ['descripcion' => 'normal - negro', 'color' => 'negro', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'flip  top ', 'color' => 'azul', 'tipo' => 'flip top', 'imagen' => 'tapas/flip.jpg'],

        ];

        foreach ($tapas as $data) {
            foreach ([1, 2] as $sucursal_id) {
                $tapa = Tapa::create([
                    'imagen' => $data['imagen'],
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
