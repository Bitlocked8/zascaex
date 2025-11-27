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
            ['descripcion' => 'normal - transparente', 'color' => 'transparente', 'tipo' => 'normal', 'imagen' => 'tapas/normal_transparente.jpg'],
            ['descripcion' => 'normal - blanco', 'color' => 'blanco', 'tipo' => 'normal', 'imagen' => 'tapas/blanca.jpg'],
            ['descripcion' => 'normal - verde', 'color' => 'verde', 'tipo' => 'normal', 'imagen' => 'tapas/normal_verde.jpg'],
            ['descripcion' => 'normal - rosado', 'color' => 'rosado', 'tipo' => 'normal', 'imagen' => 'tapas/normal_rosado.jpg'],
            ['descripcion' => 'normal - rojo', 'color' => 'rojo', 'tipo' => 'normal', 'imagen' => 'tapas/normal_rojo.jpg'],
            ['descripcion' => 'normal - azul', 'color' => 'azul', 'tipo' => 'normal', 'imagen' => 'tapas/azul.jpg'],
            ['descripcion' => 'normal - negro', 'color' => 'negro', 'tipo' => 'normal', 'imagen' => 'tapas/negra.jpg'],
            ['descripcion' => 'deportivas - rojo', 'color' => 'rojo', 'tipo' => 'deportivas', 'imagen' => 'tapas/deportivas_rojo.jpg'],
            ['descripcion' => 'deportivas - azul', 'color' => 'azul', 'tipo' => 'deportivas', 'imagen' => 'tapas/deportivas_azul.jpg'],
            ['descripcion' => 'deportivas - negro', 'color' => 'negro', 'tipo' => 'deportivas', 'imagen' => 'tapas/deportivas_negro.jpg'],
            ['descripcion' => 'deportivas - transparente', 'color' => 'transparente', 'tipo' => 'deportivas', 'imagen' => 'tapas/deportivas_transparente.jpg'],
            ['descripcion' => 'deportivas - blanco', 'color' => 'blanco', 'tipo' => 'deportivas', 'imagen' => 'tapas/deportivas_blanco.jpg'],
            ['descripcion' => 'push up - azul', 'color' => 'azul', 'tipo' => 'push up', 'imagen' => 'tapas/pushup_azul.jpg'],
            ['descripcion' => 'flip  top ', 'color' => 'azul', 'tipo' => 'flip top', 'imagen' => 'tapas/flip.jpg'],
            ['descripcion' => 'rosca - azul', 'color' => 'azul', 'tipo' => 'rosca', 'imagen' => 'tapas/rosca_azul.jpg'],
            ['descripcion' => 'rosca - blanco', 'color' => 'blanco', 'tipo' => 'rosca', 'imagen' => 'tapas/rosca_blanco.jpg'],
            ['descripcion' => 'rosca - negro', 'color' => 'negro', 'tipo' => 'rosca', 'imagen' => 'tapas/rosca_negro.jpg'],
            ['descripcion' => 'cuello largo - transparente', 'color' => 'transparente', 'tipo' => 'normal', 'imagen' => 'tapas/cuello_largo_transparente.jpg'],
            ['descripcion' => 'cuello largo - blanco', 'color' => 'blanco', 'tipo' => 'normal', 'imagen' => 'tapas/cuello_largo_blanco.jpg'],
            ['descripcion' => 'cuello largo - negro', 'color' => 'negro', 'tipo' => 'normal', 'imagen' => 'tapas/cuello_largo_negro.jpg'],
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
