<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Existencia;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            ['descripcion' => 'Botella 250 ', 'tipoContenido' => 'jugo', 'tipoProducto' => 'botella', 'capacidad' => 250, 'unidad' => 'mls', 'precioReferencia' => 3.6, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 330 ', 'tipoContenido' => 'jugo', 'tipoProducto' => 'botella', 'capacidad' => 330, 'unidad' => 'mls', 'precioReferencia' => 3.7, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 400 ', 'tipoContenido' => 'jugo', 'tipoProducto' => 'botella', 'capacidad' => 400, 'unidad' => 'mls', 'precioReferencia' => 6.5, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 500 ', 'tipoContenido' => 'jugo', 'tipoProducto' => 'botella', 'capacidad' => 500, 'unidad' => 'mls', 'precioReferencia' => 5.15, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 1 ', 'tipoContenido' => 'jugo', 'tipoProducto' => 'botella', 'capacidad' => 1, 'unidad' => 'Lt', 'precioReferencia' => 11.5, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 600 ', 'tipoContenido' => 'jugo', 'tipoProducto' => 'botella', 'capacidad' => 600, 'unidad' => 'mls', 'precioReferencia' => 5.3, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],


            ['descripcion' => 'Botella 250 ', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 250, 'unidad' => 'mls', 'precioReferencia' => 3.7, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 330 ', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 330, 'unidad' => 'mls', 'precioReferencia' => 3.9, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 400 ', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 400, 'unidad' => 'mls', 'precioReferencia' => 6.8, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 500 ', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 500, 'unidad' => 'mls', 'precioReferencia' => 4.6, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 530 ', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 530, 'unidad' => 'mls', 'precioReferencia' => 4.7, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 600 ', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 600, 'unidad' => 'mls', 'precioReferencia' => 4.7, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 750 ', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 750, 'unidad' => 'mls', 'precioReferencia' => 6, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 1 ', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 1, 'unidad' => 'Lt', 'precioReferencia' => 6.3, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 1 ', 'tipoContenido' => 'Agua alcalino', 'tipoProducto' => 'botella', 'capacidad' => 1, 'unidad' => 'Lt', 'precioReferencia' => 7.5, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],

            ['descripcion' => 'Botella 400 ', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 400, 'unidad' => 'mls', 'precioReferencia' => 23.1, 'paquete' => null, 'tipo' => 'Vidrio', 'observaciones' => null],

            ['descripcion' => 'Botellon 20  retorable', 'tipoContenido' => null, 'tipoProducto' => 'botellon', 'capacidad' => 20, 'unidad' => 'Lt', 'precioReferencia' => 16, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botellon 10  retorable', 'tipoContenido' => null, 'tipoProducto' => 'botellon', 'capacidad' => 10, 'unidad' => 'Lt', 'precioReferencia' => 12, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => 'prestamo contrato'],
            ['descripcion' => 'Botellon 20 venta', 'tipoContenido' => null, 'tipoProducto' => 'botellon', 'capacidad' => 20, 'unidad' => 'Lt', 'precioReferencia' => 70, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => 'prestamo contrato'],
            ['descripcion' => 'Botellon 10 venta', 'tipoContenido' => null, 'tipoProducto' => 'botellon', 'capacidad' => 10, 'unidad' => 'Lt', 'precioReferencia' => 35, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => 'prestamo contrato'],
        ];

        foreach ($productos as $data) {
            foreach ([1, 2] as $sucursal_id) {
                $producto = Producto::create([
                    'imagen' => 'productos/bot1.jpg',
                    'unidad' => $data['unidad'],
                    'descripcion' => $data['descripcion'],
                    'tipoContenido' => $data['tipoContenido'],
                    'tipoProducto' => $data['tipoProducto'],
                    'capacidad' => $data['capacidad'],
                    'precioReferencia' => $data['precioReferencia'],
                    'paquete' => $data['paquete'],
                    'observaciones' => $data['observaciones'],
                    'estado' => 1,
                    'tipo' => $data['tipo'],
                ]);

                // Crear existencia en cada sucursal
                Existencia::create([
                    'existenciable_type' => Producto::class,
                    'existenciable_id' => $producto->id,
                    'cantidad' => 0,
                    'cantidadMinima' => 0,
                    'sucursal_id' => $sucursal_id,
                ]);
            }
        }
    }
}
