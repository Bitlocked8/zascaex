<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Otro;
use App\Models\Existencia;

class OtroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            ['descripcion' => 'Hielo 2,5', 'tipoContenido' => 'hielo', 'tipoProducto' => null, 'capacidad' => 2.5, 'unidad' => 'Kgs', 'precioReferencia' => 4.0, 'tipo' => null],
            ['descripcion' => 'Hielo 5', 'tipoContenido' => 'hielo', 'tipoProducto' => null, 'capacidad' => 5, 'unidad' => 'Kgs', 'precioReferencia' => 7.0, 'tipo' => null],
            ['descripcion' => 'Dispensador frio caliente de escritorio', 'tipoContenido' => null, 'tipoProducto' => 'dispenser', 'capacidad' => null, 'unidad' => null, 'precioReferencia' => 500, 'tipo' => 'Plastico'],
            ['descripcion' => 'Dispensador frio caliente alto', 'tipoContenido' => null, 'tipoProducto' => 'dispenser', 'capacidad' => null, 'unidad' => null, 'precioReferencia' => 900, 'tipo' => 'Plastico'],
        ];

        foreach ($productos as $data) {
            foreach ([1, 2] as $sucursal_id) {
                $otro = Otro::create([
                    'imagen' => 'otros/default.jpg',
                    'unidad' => $data['unidad'],
                    'descripcion' => $data['descripcion'],
                    'tipoContenido' => $data['tipoContenido'],
                    'tipoProducto' => $data['tipoProducto'],
                    'capacidad' => $data['capacidad'],
                    'precioReferencia' => $data['precioReferencia'],
                    'paquete' => null,
                    'observaciones' => null,
                    'estado' => 1,
                    'tipo' => $data['tipo'],
                ]);

                Existencia::create([
                    'existenciable_type' => Otro::class,
                    'existenciable_id' => $otro->id,
                    'cantidad' => 0,
                    'cantidadMinima' => 0,
                    'sucursal_id' => $sucursal_id,
                ]);
            }
        }
    }
}
