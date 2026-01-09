<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Otro;
use App\Models\Existencia;

class OtroSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            ['descripcion' => 'Hielo 2,5', 'tipoContenido' => 'hielo', 'tipoProducto' => 2, 'capacidad' => 2.5, 'unidad' => 'Kgs', 'precioReferencia' => 4.0, 'precioAlternativo' => null, 'paquete' => null, 'tipo' => null, 'observaciones' => null, 'imagen' => 'otros/hielo25.jpeg'],
            ['descripcion' => 'Hielo 5', 'tipoContenido' => 'hielo', 'tipoProducto' => 2, 'capacidad' => 5, 'unidad' => 'Kgs', 'precioReferencia' => 7.0, 'precioAlternativo' => null, 'paquete' => null, 'tipo' => null, 'observaciones' => null, 'imagen' => 'otros/hielo25.jpeg'],
            ['descripcion' => 'Dispensador frio caliente de escritorio', 'tipoContenido' => null, 'tipoProducto' => 3, 'capacidad' => null, 'unidad' => null, 'precioReferencia' => 500, 'precioAlternativo' => null, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'otros/dispenserv.png'],
            ['descripcion' => 'Dispensador frio caliente alto', 'tipoContenido' => null, 'tipoProducto' => 3, 'capacidad' => null, 'unidad' => null, 'precioReferencia' => 900, 'precioAlternativo' => null, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'otros/dispenserv.png'],
            ['descripcion' => 'Botellon 20 retorable', 'tipoContenido' => null, 'tipoProducto' => 1, 'capacidad' => 20, 'unidad' => 'Lt', 'precioReferencia' => 16, 'precioAlternativo' => null, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/bot10.jpeg'],
            ['descripcion' => 'Botellon 10 retorable', 'tipoContenido' => null, 'tipoProducto' => 1, 'capacidad' => 10, 'unidad' => 'Lt', 'precioReferencia' => 12, 'precioAlternativo' => null, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => 'prestamo contrato', 'imagen' => 'productos/bot10.jpeg'],
            ['descripcion' => 'Botellon 20 venta', 'tipoContenido' => null, 'tipoProducto' => 1, 'capacidad' => 20, 'unidad' => 'Lt', 'precioReferencia' => 70, 'precioAlternativo' => null, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => 'prestamo contrato', 'imagen' => 'productos/bot20.jpeg'],
            ['descripcion' => 'Botellon 10 venta', 'tipoContenido' => null, 'tipoProducto' => 1, 'capacidad' => 10, 'unidad' => 'Lt', 'precioReferencia' => 35, 'precioAlternativo' => null, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => 'prestamo contrato', 'imagen' => 'productos/bot20.jpeg'],
        ];

        foreach ($productos as $data) {
            foreach ([1, 2] as $sucursal_id) {
                $otro = Otro::create([
                    'imagen' => $data['imagen'],
                    'unidad' => $data['unidad'],
                    'descripcion' => $data['descripcion'],
                    'tipoContenido' => $data['tipoContenido'],
                    'tipoProducto' => $data['tipoProducto'],
                    'capacidad' => $data['capacidad'],
                    'precioReferencia' => $data['precioReferencia'],
                    'precioAlternativo' => $data['precioAlternativo'],
                    'paquete' => $data['paquete'],
                    'observaciones' => $data['observaciones'],
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
