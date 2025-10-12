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
            ['descripcion' => 'Botella 250 mls', 'tipoContenido' => 'agua normal', 'tipoProducto' => 'botella', 'capacidad' => 250, 'unidad' => 'mls', 'precioReferencia' => 3.2, 'paquete' => '35 unidades', 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 330 mls', 'tipoContenido' => 'agua normal', 'tipoProducto' => 'botella', 'capacidad' => 330, 'unidad' => 'mls', 'precioReferencia' => 3.6, 'paquete' => '30 unidades', 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 400 mls', 'tipoContenido' => 'agua normal', 'tipoProducto' => 'botella', 'capacidad' => 400, 'unidad' => 'mls', 'precioReferencia' => 6.0, 'paquete' => '20 unidades', 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 500 mls', 'tipoContenido' => 'agua normal', 'tipoProducto' => 'botella', 'capacidad' => 500, 'unidad' => 'mls', 'precioReferencia' => 4.0, 'paquete' => '20 unidades', 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 530 mls', 'tipoContenido' => 'agua con gas', 'tipoProducto' => 'botella', 'capacidad' => 530, 'unidad' => 'mls', 'precioReferencia' => 4.2, 'paquete' => '20 unidades', 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 600 mls', 'tipoContenido' => 'agua normal', 'tipoProducto' => 'botella', 'capacidad' => 600, 'unidad' => 'mls', 'precioReferencia' => 22.5, 'paquete' => '20 unidades', 'tipo' => 'Vidrio', 'observaciones' => null],
            ['descripcion' => 'Botella 600 mls', 'tipoContenido' => 'agua alcalina', 'tipoProducto' => 'botella', 'capacidad' => 600, 'unidad' => 'mls', 'precioReferencia' => 4.8, 'paquete' => '20 unidades', 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 600 mls', 'tipoContenido' => 'agua normal', 'tipoProducto' => 'botella', 'capacidad' => 600, 'unidad' => 'mls', 'precioReferencia' => 4.5, 'paquete' => '20 unidades', 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 750 mls', 'tipoContenido' => 'agua normal', 'tipoProducto' => 'botella', 'capacidad' => 750, 'unidad' => 'mls', 'precioReferencia' => 5.0, 'paquete' => '15 unidades', 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 1 lt', 'tipoContenido' => 'agua normal', 'tipoProducto' => 'botella', 'capacidad' => 1, 'unidad' => 'Lt', 'precioReferencia' => 5.5, 'paquete' => '10 unidades', 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 1 lt', 'tipoContenido' => 'agua alcalina', 'tipoProducto' => 'botella', 'capacidad' => 1, 'unidad' => 'Lt', 'precioReferencia' => 5.7, 'paquete' => '10 unidades', 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 1,5 lts', 'tipoContenido' => 'agua normal', 'tipoProducto' => 'botella', 'capacidad' => 1.5, 'unidad' => 'Lts', 'precioReferencia' => 5.8, 'paquete' => '8 unidades', 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botella 1,5 lts', 'tipoContenido' => 'agua alcalina', 'tipoProducto' => 'botella', 'capacidad' => 1.5, 'unidad' => 'Lts', 'precioReferencia' => 6.0, 'paquete' => '8 unidades', 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botellon 10 lts', 'tipoContenido' => 'agua normal', 'tipoProducto' => 'botellon', 'capacidad' => 10, 'unidad' => 'Lts', 'precioReferencia' => 10.0, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botellon 20 lts', 'tipoContenido' => 'agua normal', 'tipoProducto' => 'botellon', 'capacidad' => 20, 'unidad' => 'Lts', 'precioReferencia' => 16.0, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Hielo 2,5', 'tipoContenido' => 'hielo', 'tipoProducto' => null, 'capacidad' => 2.5, 'unidad' => 'Kgs', 'precioReferencia' => 4.0, 'paquete' => null, 'tipo' => null, 'observaciones' => null],
            ['descripcion' => 'Hielo 5', 'tipoContenido' => 'hielo', 'tipoProducto' => null, 'capacidad' => 5, 'unidad' => 'kgs', 'precioReferencia' => 7.0, 'paquete' => null, 'tipo' => null, 'observaciones' => null],
            ['descripcion' => 'Dispenser', 'tipoContenido' => null, 'tipoProducto' => 'dispenser', 'capacidad' => null, 'unidad' => null, 'precioReferencia' => 40.0, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Botellon 20 lts', 'tipoContenido' => null, 'tipoProducto' => 'botellon', 'capacidad' => 20, 'unidad' => 'Lts', 'precioReferencia' => 40.0, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => null],
            ['descripcion' => 'Equipo Frio Caliente', 'tipoContenido' => null, 'tipoProducto' => 'dispenser', 'capacidad' => null, 'unidad' => null, 'precioReferencia' => 0, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => 'prestamo contrato'],
            ['descripcion' => 'Botellon 10 lts', 'tipoContenido' => null, 'tipoProducto' => 'botellon', 'capacidad' => 10, 'unidad' => 'Lts', 'precioReferencia' => 0, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => 'prestamo contrato'],
            ['descripcion' => 'Botellon 20 lts', 'tipoContenido' => null, 'tipoProducto' => 'botellon', 'capacidad' => 20, 'unidad' => 'Lts', 'precioReferencia' => 0, 'paquete' => null, 'tipo' => 'Plastico', 'observaciones' => 'prestamo contrato'],
        ];

       foreach ($productos as $data) {
            foreach ([1, 2] as $sucursal_id) {
                $producto = Producto::create([
                    'imagen' => null,
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
