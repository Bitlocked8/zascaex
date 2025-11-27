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
            ['descripcion' => 'Botella 250', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 'botella', 'capacidad' => 250, 'unidad' => 'mls', 'precioReferencia' => 3.6, 'paquete' => 35, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/250.jpg'],
            ['descripcion' => 'Botella 330', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 'botella', 'capacidad' => 330, 'unidad' => 'mls', 'precioReferencia' => 3.7, 'paquete' => 30, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/330.jpg'],
            ['descripcion' => 'Botella 400', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 'botella', 'capacidad' => 400, 'unidad' => 'mls', 'precioReferencia' => 6.5, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/400.jpg'],
            ['descripcion' => 'Botella 500', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 'botella', 'capacidad' => 500, 'unidad' => 'mls', 'precioReferencia' => 5.15, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500.jpg'],
            ['descripcion' => 'Botella 1 lt', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 'botella', 'capacidad' => 1000, 'unidad' => 'Lt', 'precioReferencia' => 11.5, 'paquete' => 15, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/1lt.jpg'],
            ['descripcion' => 'Botella 600', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 'botella', 'capacidad' => 600, 'unidad' => 'mls', 'precioReferencia' => 5.3, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/600ml.jpg'],
            ['descripcion' => 'Botella 1.5 lt', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 'botella', 'capacidad' => 1500, 'unidad' => 'Lt', 'precioReferencia' => 7.5, 'paquete' => 8, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/botella_1_5lt_saborizada.jpg'],
            ['descripcion' => 'Botella 750', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 'botella', 'capacidad' => 750, 'unidad' => 'mls', 'precioReferencia' => 6, 'paquete' => 15, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/botella_750_saborizada.jpg'],

            // Agua normal
            ['descripcion' => 'Botella 250', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 250, 'unidad' => 'mls', 'precioReferencia' => 3.7, 'paquete' => 35, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/250.jpg'],
            ['descripcion' => 'Botella 330', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 330, 'unidad' => 'mls', 'precioReferencia' => 3.9, 'paquete' => 30, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/330.jpg'],
            ['descripcion' => 'Botella 400', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 400, 'unidad' => 'mls', 'precioReferencia' => 6.8, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/400.jpg'],
            ['descripcion' => 'Botella 500', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 500, 'unidad' => 'mls', 'precioReferencia' => 4.6, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500.jpg'],
            ['descripcion' => 'Botella 530', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 530, 'unidad' => 'mls', 'precioReferencia' => 4.7, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/530ml.jpg'],
            ['descripcion' => 'Botella 600', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 600, 'unidad' => 'mls', 'precioReferencia' => 4.7, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/600ml.jpg'],
            ['descripcion' => 'Botella 750', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 750, 'unidad' => 'mls', 'precioReferencia' => 6, 'paquete' => 15, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/botella_750_agua_normal.jpg'],
            ['descripcion' => 'Botella 1', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 1000, 'unidad' => 'Lt', 'precioReferencia' => 6.3, 'paquete' => 15, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/1lts.jpg'],

            // Agua alcalina
            ['descripcion' => 'Botella 1.5 lt', 'tipoContenido' => 'Agua alcalina', 'tipoProducto' => 'botella', 'capacidad' => 1500, 'unidad' => 'Lt', 'precioReferencia' => 7.5, 'paquete' => 8, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/alcalina.jpg'],

            // Vidrio
            ['descripcion' => 'Botella 400', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 'botella', 'capacidad' => 400, 'unidad' => 'mls', 'precioReferencia' => 23.1, 'paquete' => null, 'tipo' => 'Vidrio', 'observaciones' => null, 'imagen' => 'productos/botella_400_vidrio.jpg'],

            // Botellones
            ['descripcion' => 'Botellon 20 retorable', 'tipoContenido' => null, 'tipoProducto' => 'botellon', 'capacidad' => 20, 'unidad' => 'Lt', 'precioReferencia' => 16, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/botellon.jpg'],
            ['descripcion' => 'Botellon 10 retorable', 'tipoContenido' => null, 'tipoProducto' => 'botellon', 'capacidad' => 10, 'unidad' => 'Lt', 'precioReferencia' => 12, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => 'prestamo contrato', 'imagen' => 'productos/botellon.jpg'],
            ['descripcion' => 'Botellon 20 venta', 'tipoContenido' => null, 'tipoProducto' => 'botellon', 'capacidad' => 20, 'unidad' => 'Lt', 'precioReferencia' => 70, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => 'prestamo contrato', 'imagen' => 'productos/botellon1.jpg'],
            ['descripcion' => 'Botellon 10 venta', 'tipoContenido' => null, 'tipoProducto' => 'botellon', 'capacidad' => 10, 'unidad' => 'Lt', 'precioReferencia' => 35, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => 'prestamo contrato', 'imagen' => 'productos/botellon1.jpg'],
        ];

        foreach ($productos as $data) {
            foreach ([1, 2] as $sucursal_id) {
                $producto = Producto::create([
                    'imagen' => $data['imagen'],
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
