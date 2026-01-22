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

            ['descripcion' => 'Vidrio - plomo', 'color' => 'negro', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Vidrio - azul', 'color' => 'azul', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Vidrio - blanco', 'color' => 'blanco', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Apolo - rosado', 'color' => 'rosado', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Apolo - azul', 'color' => 'azul', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Apolo - negro', 'color' => 'negro', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Apolo - transparente', 'color' => 'transparente', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Apolo -Cuello alto - transparente', 'color' => 'transparente', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Apolo -Cuello alto - negro', 'color' => 'negro', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Apolo -Cuello alto - blanco', 'color' => 'blanco', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Preforsa - transparente', 'color' => 'transparente', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Preforsa - azul', 'color' => 'azul', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Deportiva - azul', 'color' => 'azul', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Deportiva - blanco', 'color' => 'blanco', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Deportiva - negro', 'color' => 'negro', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Deportiva - rojo', 'color' => 'rojo', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Botellon - blanco', 'color' => 'blanco', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'Botellon - negro', 'color' => 'negro', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],

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
