<?php

namespace Database\Seeders;

use App\Models\Embotellado;
use App\Models\Asignacion;
use App\Models\Base;
use App\Models\Coche;
use App\Models\Distribucion;
use App\Models\Empresa;
use App\Models\Personal;
use App\Models\Stock;
use App\Models\Sucursal;
use App\Models\Cliente;
use App\Models\Compra;
use App\Models\Elaboracion;
use App\Models\Etiqueta;
use App\Models\Etiquetado;
use App\Models\Existencia;
use App\Models\Itemcompra;
use App\Models\Itemventa;
use App\Models\Pagoventa;
use App\Models\Preforma;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Rol;
use App\Models\Tapa;
use App\Models\Trabajo;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear un usuario con la contraseña '12345678' hasheada
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'password' => bcrypt('12345678'),  // Hasheamos la contraseña
        // ]);
        // Cliente::factory(5)->create();


        Rol::create(['nombre' => 'Super administrador', 'descripcion' => 'Acceso total al sistema']);
        Rol::create(['nombre' => 'Administrador', 'descripcion' => 'Acceso acceso a ventas']);
        Rol::create(['nombre' => 'Distribuidor', 'descripcion' => 'Acceso acceso a ventas']);
        Rol::create(['nombre' => 'Planta', 'descripcion' => 'Acceso acceso a ventas']);
        Rol::create(['nombre' => 'Cliente', 'descripcion' => 'Acceso la aplicacion móvil, catálogo y compras por internet.']);

        $adminUser = User::create([
            'email' => 'admin@mail.com',
            'password' => bcrypt(12345678),
            'estado' => 1,
            'rol_id' => 1,
        ]);

        // Asignar un Personal para el usuario admin
        Personal::factory()->create([
            'user_id' => $adminUser->id, // Relación con el usuario admin
        ]);

        // User::factory(10)->create();
        Cliente::factory(10)->create();

        $this->call(ClienteSeeder::class);
        Proveedor::factory(10)->create();
        $empresa = Empresa::factory(1)->create();
        Sucursal::create(['nombre' => 'Cochabamba Central', 'direccion' => 'Av. Heroínas 123, Cochabamba, Bolivia', 'telefono' => '591 4 4251234', 'zona' => 'Centro', 'empresa_id' => 1]);
        Sucursal::create(['nombre' => 'Santa Cruz Norte', 'direccion' => 'Av. Banzer 456, Santa Cruz, Bolivia', 'telefono' => '591 3 3435678', 'zona' => 'Norte', 'empresa_id' => 1]);
        $sucursales = Sucursal::all();
        $emailSucursal = ['cochabamba@mail.com', 'santacruzmail.com'];
        foreach ($sucursales as $index => $sucursal) {
            $sucursal->email = $emailSucursal[$index] ?? null;
        }


        $preformas = [
            [
                'imagen' => 'preformas/ppet002.jpg',
                'detalle' => 'mls',
                'insumo' => 'botella',
                'gramaje' => '250 - 330',
                'cuello' => 'corto',
                'descripcion' => 'normal',
                'capacidad' => 5000,
                'color' => 'transparente',
                'estado' => 1,
                'observaciones' => null,
            ],
            [
                'imagen' => 'preformas/ppet001.jpg',
                'detalle' => 'mls',
                'insumo' => 'botella',
                'gramaje' => '250',
                'cuello' => 'largo',
                'descripcion' => 'normal',
                'capacidad' => 5000,
                'color' => 'transparente',
                'estado' => 1,
                'observaciones' => null,
            ],
            [
                'imagen' => 'preformas/ppet004.jpg',
                'detalle' => 'mls',
                'insumo' => 'botella',
                'gramaje' => '500-600-750',
                'cuello' => 'corto',
                'descripcion' => 'normal, con gas, tornado bicentenario, everest',
                'capacidad' => 5000,
                'color' => 'transparente',
                'estado' => 1,
                'observaciones' => null,
            ],
            [
                'imagen' => 'preformas/ppet005.jpg',
                'detalle' => 'mls',
                'insumo' => 'botella',
                'gramaje' => '500-600-750',
                'cuello' => 'corto',
                'descripcion' => 'normal, con gas, tornado bicentenario, everest',
                'capacidad' => 5000,
                'color' => 'azul',
                'estado' => 1,
                'observaciones' => null,
            ],
            [
                'imagen' => 'preformas/ppet006.jpg',
                'detalle' => 'Lts',
                'insumo' => 'botella',
                'gramaje' => '1 - 1,5',
                'cuello' => 'Bajo', // Valor predeterminado
                'descripcion' => 'normal',
                'capacidad' => 5000,
                'color' => 'transparente',
                'estado' => 1,
                'observaciones' => null,
            ],
            [
                'imagen' => 'preformas/ppet007.jpg',
                'detalle' => 'Lts',
                'insumo' => 'botella',
                'gramaje' => '1 - 1,5',
                'cuello' => 'Bajo', // Valor predeterminado
                'descripcion' => 'normal',
                'capacidad' => 5000,
                'color' => 'azul',
                'estado' => 1,
                'observaciones' => null,
            ],
            [
                'imagen' => 'preformas/ppet008.jpg',
                'detalle' => 'Lts',
                'insumo' => 'botellon',
                'gramaje' => '20',
                'cuello' => 'Bajo', // Valor predeterminado
                'descripcion' => 'normal',
                'capacidad' => 300,
                'color' => 'azul',
                'estado' => 1,
                'observaciones' => null,
            ],
            [
                'imagen' => 'preformas/ppet003.jpg',
                'detalle' => 'mls',
                'insumo' => 'botella',
                'gramaje' => '400',
                'cuello' => 'Bajo', // Valor predeterminado
                'descripcion' => 'normal',
                'capacidad' => 5000,
                'color' => 'transparente',
                'estado' => 1,
                'observaciones' => null,
            ],
        ];

        foreach ($preformas as $preforma) {
            Preforma::create($preforma);
        }


        // Sembrar datos para la tabla bases
        $bases = [
            [
                'imagen' => 'bases/ppref001.jpg',
                'descripcion' => 'mls',
                'capacidad' => 250,
                'estado' => 1,
                'observaciones' => 'normal, cuello corto',
                'preforma_id' => 2, // ppet002
            ],
            [
                'imagen' => 'bases/ppref002.jpg',
                'descripcion' => 'mls',
                'capacidad' => 250,
                'estado' => 1,
                'observaciones' => 'normal, cuello largo',
                'preforma_id' => 1, // ppet001
            ],
            [
                'imagen' => 'bases/ppref003.jpg',
                'descripcion' => 'mls',
                'capacidad' => 330,
                'estado' => 1,
                'observaciones' => 'normal, cuello corto',
                'preforma_id' => 2, // ppet002
            ],
            [
                'imagen' => 'bases/ppref004.jpg',
                'descripcion' => 'mls',
                'capacidad' => 400,
                'estado' => 1,
                'observaciones' => 'normal',
                'preforma_id' => 8, // ppet003
            ],
            [
                'imagen' => 'bases/ppref005.jpg',
                'descripcion' => 'mls',
                'capacidad' => 500,
                'estado' => 1,
                'observaciones' => 'normal, cuello corto',
                'preforma_id' => 3, // ppet004
            ],
            [
                'imagen' => 'bases/ppref006.jpg',
                'descripcion' => 'mls',
                'capacidad' => 530,
                'estado' => 1,
                'observaciones' => 'con gas, cuello corto',
                'preforma_id' => 3, // ppet004
            ],
            [
                'imagen' => 'bases/ppref007.jpg',
                'descripcion' => 'mls',
                'capacidad' => 500,
                'estado' => 1,
                'observaciones' => 'normal, cuello corto',
                'preforma_id' => 4, // ppet005
            ],
            [
                'imagen' => 'bases/ppref008.jpg',
                'descripcion' => 'mls',
                'capacidad' => 530,
                'estado' => 1,
                'observaciones' => 'con gas, cuello corto',
                'preforma_id' => 4, // ppet005
            ],
            [
                'imagen' => 'bases/ppref009.jpg',
                'descripcion' => 'mls',
                'capacidad' => 600,
                'estado' => 1,
                'observaciones' => 'normal',
                'preforma_id' => 3, // ppet004
            ],
            [
                'imagen' => 'bases/ppref010.jpg',
                'descripcion' => 'mls',
                'capacidad' => 600,
                'estado' => 1,
                'observaciones' => 'alcalina',
                'preforma_id' => 4, // ppet005
            ],
            [
                'imagen' => 'bases/ppref011.jpg',
                'descripcion' => 'mls',
                'capacidad' => 750,
                'estado' => 1,
                'observaciones' => 'normal',
                'preforma_id' => 3, // ppet004
            ],
            [
                'imagen' => 'bases/ppref012.jpg',
                'descripcion' => 'mls',
                'capacidad' => 750,
                'estado' => 1,
                'observaciones' => 'alcalina',
                'preforma_id' => 4, // ppet005
            ],
            [
                'imagen' => 'bases/ppref013.jpg',
                'descripcion' => 'Lt',
                'capacidad' => 1000,
                'estado' => 1,
                'observaciones' => 'normal',
                'preforma_id' => 5, // ppet006
            ],
            [
                'imagen' => 'bases/ppref014.jpg',
                'descripcion' => 'Lt',
                'capacidad' => 1000,
                'estado' => 1,
                'observaciones' => 'alcalina',
                'preforma_id' => 6, // ppet007
            ],
            [
                'imagen' => 'bases/ppref015.jpg',
                'descripcion' => 'Lts',
                'capacidad' => 1500,
                'estado' => 1,
                'observaciones' => 'normal',
                'preforma_id' => 5, // ppet006
            ],
            [
                'imagen' => 'bases/ppref016.jpg',
                'descripcion' => 'Lts',
                'capacidad' => 1500,
                'estado' => 1,
                'observaciones' => 'alcalina',
                'preforma_id' => 6, // ppet007
            ],
            [
                'imagen' => 'bases/ppref017.jpg',
                'descripcion' => 'Lts',
                'capacidad' => 20000,
                'estado' => 1,
                'observaciones' => 'normal',
                'preforma_id' => 7, // ppet008
            ],
        ];

        foreach ($bases as $base) {
            Base::create($base);
        }

        // Sembrar datos para la tabla tapas
        $tapas = [
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'transparente',
                'tipo' => 'normal',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'blanco',
                'tipo' => 'normal',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'verde',
                'tipo' => 'normal',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'rosado',
                'tipo' => 'normal',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'rojo',
                'tipo' => 'normal',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'azul',
                'tipo' => 'normal',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'negro',
                'tipo' => 'normal',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'rojo',
                'tipo' => 'deportivas',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'azul',
                'tipo' => 'deportivas',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'negro',
                'tipo' => 'deportivas',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'transparente',
                'tipo' => 'deportivas',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'blanco',
                'tipo' => 'deportivas',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'azul',
                'tipo' => 'push up',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'rojo',
                'tipo' => 'push up',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'azul',
                'tipo' => 'rosca',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'blanco',
                'tipo' => 'rosca',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => null,
                'color' => 'negro',
                'tipo' => 'rosca',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => 'cuello largo',
                'color' => 'transparente',
                'tipo' => 'normal',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => 'cuello largo',
                'color' => 'blanco',
                'tipo' => 'normal',
                'estado' => 1,
            ],
            [
                'imagen' => null,
                'descripcion' => 'cuello largo',
                'color' => 'negro',
                'tipo' => 'normal',
                'estado' => 1,
            ],
        ];

        foreach ($tapas as $tapa) {
            Tapa::create($tapa);
        }

        // Sembrar datos para la tabla etiquetas
        $etiquetas = [
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '250',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '330',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '400',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '500',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'con gas',
                'capacidad' => '530',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '600',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'alcalina',
                'capacidad' => '600',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'bicentenario normal',
                'capacidad' => '600',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'bicentenario alcalina',
                'capacidad' => '600',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '750',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'alcalina',
                'capacidad' => '750',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '1',
                'unidad' => 'L',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'alcalina',
                'capacidad' => '1',
                'unidad' => 'L',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '1.5',
                'unidad' => 'L',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'alcalina',
                'capacidad' => '1.5',
                'unidad' => 'L',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'dispenser',
                'capacidad' => '20',
                'unidad' => 'L',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '250',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '330',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '400',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '500',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'con gas',
                'capacidad' => '530',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '600',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'alcalina',
                'capacidad' => '600',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'bicentenario normal',
                'capacidad' => '600',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'bicentenario alcalina',
                'capacidad' => '600',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '750',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'alcalina',
                'capacidad' => '750',
                'unidad' => 'ml',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '1',
                'unidad' => 'L',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'alcalina',
                'capacidad' => '1',
                'unidad' => 'L',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '1.5',
                'unidad' => 'L',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'alcalina',
                'capacidad' => '1.5',
                'unidad' => 'L',
                'estado' => 1,
                'cliente_id' => null,
            ],
            [
                'imagen' => null,
                'descripcion' => 'normal',
                'capacidad' => '20',
                'unidad' => 'L',
                'estado' => 1,
                'cliente_id' => null,
            ],
        ];

        foreach ($etiquetas as $etiqueta) {
            Etiqueta::create($etiqueta);
        }

        // Sembrar datos para la tabla productos
        $productos = [
            [
                'nombre' => 'Botella 250 mls',
                'imagen' => null,
                'tipoContenido' => 1, // agua normal
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 250,
                'unidad' => 'ml',
                'precioReferencia' => 3.20,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '35 unidades, Plastico',
                'base_id' => 2, // ppref002
                'tapa_id' => 1, // t0001
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 250 mls',
                'imagen' => null,
                'tipoContenido' => 1, // agua normal
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 250,
                'unidad' => 'ml',
                'precioReferencia' => 3.20,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '35 unidades, Plastico',
                'base_id' => 1, // ppref001
                'tapa_id' => 19, // t0019
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 330 mls',
                'imagen' => null,
                'tipoContenido' => 1, // agua normal
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 330,
                'unidad' => 'ml',
                'precioReferencia' => 3.60,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '30 unidades, Plastico',
                'base_id' => 3, // ppref003
                'tapa_id' => 1, // t0001
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 400 mls',
                'imagen' => null,
                'tipoContenido' => 1, // agua normal
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 400,
                'unidad' => 'ml',
                'precioReferencia' => 6.00,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '20 unidades, Plastico',
                'base_id' => 4, // ppref004
                'tapa_id' => 15, // t0015
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 400 mls',
                'imagen' => null,
                'tipoContenido' => 1, // agua normal
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 400,
                'unidad' => 'ml',
                'precioReferencia' => 6.00,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '20 unidades, Plastico',
                'base_id' => 5, // ppref005
                'tapa_id' => 17, // t0017
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 500 mls',
                'imagen' => null,
                'tipoContenido' => 1, // agua normal
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 500,
                'unidad' => 'ml',
                'precioReferencia' => 4.00,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '20 unidades, Plastico',
                'base_id' => 6, // ppref006
                'tapa_id' => 1, // t0001
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 530 mls',
                'imagen' => null,
                'tipoContenido' => 2, // agua con gas
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 530,
                'unidad' => 'ml',
                'precioReferencia' => 4.20,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '20 unidades, Plastico',
                'base_id' => 7, // ppref007
                'tapa_id' => 1, // t0001
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 600 mls',
                'imagen' => null,
                'tipoContenido' => 1, // agua normal
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 600,
                'unidad' => 'ml',
                'precioReferencia' => 22.50,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '20 unidades, Vidrio',
                'base_id' => 8, // ppref008
                'tapa_id' => 1, // t0001
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 600 mls',
                'imagen' => null,
                'tipoContenido' => 3, // agua alcalina
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 600,
                'unidad' => 'ml',
                'precioReferencia' => 4.80,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '20 unidades, Plastico',
                'base_id' => 9, // ppref009
                'tapa_id' => 1, // t0001
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 600 mls',
                'imagen' => null,
                'tipoContenido' => 1, // agua normal
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 600,
                'unidad' => 'ml',
                'precioReferencia' => 4.50,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '20 unidades, Plastico',
                'base_id' => 10, // ppref010
                'tapa_id' => 1, // t0001
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 750 mls',
                'imagen' => null,
                'tipoContenido' => 1, // agua normal
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 750,
                'unidad' => 'ml',
                'precioReferencia' => 5.00,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '15 unidades, Plastico',
                'base_id' => 11, // ppref011
                'tapa_id' => 1, // t0001
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 1 lt',
                'imagen' => null,
                'tipoContenido' => 1, // agua normal
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 1000,
                'unidad' => 'L',
                'precioReferencia' => 5.50,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '10 unidades, Plastico',
                'base_id' => 13, // ppref013
                'tapa_id' => 1, // t0001
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 1 lt',
                'imagen' => null,
                'tipoContenido' => 3, // agua alcalina
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 1000,
                'unidad' => 'L',
                'precioReferencia' => 5.70,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '10 unidades, Plastico',
                'base_id' => 14, // ppref014
                'tapa_id' => 1, // t0001
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 1,5 lts',
                'imagen' => null,
                'tipoContenido' => 1, // agua normal
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 1500,
                'unidad' => 'L',
                'precioReferencia' => 5.80,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '8 unidades, Plastico',
                'base_id' => 15, // ppref015
                'tapa_id' => 1, // t0001
                'estado' => 1,
            ],
            [
                'nombre' => 'Botella 1,5 lts',
                'imagen' => null,
                'tipoContenido' => 3, // agua alcalina
                'tipoProducto' => 0, // botella (sin retorno)
                'capacidad' => 1500,
                'unidad' => 'L',
                'precioReferencia' => 6.00,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => '8 unidades, Plastico',
                'base_id' => 16, // ppref016
                'tapa_id' => 1, // t0001
                'estado' => 1,
            ],
            [
                'nombre' => 'Botellon 20 lts',
                'imagen' => null,
                'tipoContenido' => 1, // agua normal
                'tipoProducto' => 1, // botellon (con retorno)
                'capacidad' => 20000,
                'unidad' => 'L',
                'precioReferencia' => 16.00,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => 'Plastico',
                'base_id' => 17, // ppref017
                'tapa_id' => null,
                'estado' => 1,
            ],
            [
                'nombre' => 'Hielo 2,5',
                'imagen' => null,
                'tipoContenido' => 4, // hielo
                'tipoProducto' => 0, // sin retorno
                'capacidad' => 2500,
                'unidad' => 'kg',
                'precioReferencia' => 4.00,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => null,
                'base_id' => 1, // ppref001 (temporal, ajustar según necesidad)
                'tapa_id' => null,
                'estado' => 1,
            ],
            [
                'nombre' => 'Hielo 5',
                'imagen' => null,
                'tipoContenido' => 4, // hielo
                'tipoProducto' => 0, // sin retorno
                'capacidad' => 5000,
                'unidad' => 'kg',
                'precioReferencia' => 7.00,
                'precioReferencia2' => null,
                'precioReferencia3' => null,
                'observaciones' => null,
                'base_id' => 1, // ppref001 (temporal, ajustar según necesidad)
                'tapa_id' => null,
                'estado' => 1,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }

        $preformas = Preforma::get();
        $bases = Base::get();
        $tapas = Tapa::get();
        $productos = Producto::get();
        $etiquetas = Etiqueta::get();
        $stocks = Stock::factory(5)->create();



        Coche::factory(5)->create()->each(function ($coche) {
            // Crear una asignación para el coche
            $asignacion = Asignacion::create([
                'fechaInicio' => now(),
                'fechaFinal' => now()->addMonths(6), // 6 meses de asignación por defecto
                'estado' => 1, // Activo
                'coche_id' => $coche->id,
                'personal_id' => Personal::inRandomOrder()->first()->id, // Asignar un personal aleatorio
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Crear una distribución para la asignación recién creada
            Distribucion::factory()->create([
                'asignacion_id' => $asignacion->id,
                'fecha' => now(), // Fecha de distribución
                'estado' => 1, // En distribución
                'observaciones' => "Distribución generada automáticamente.",
            ]);
        });

        // Asignar existencias a cada sucursal
        foreach ($sucursales as $sucursal) {
            // Asignar preformas a la sucursal
            foreach ($preformas as $preforma) {
                Existencia::create([
                    'existenciable_id' => $preforma->id,
                    'existenciable_type' => Preforma::class,
                    'sucursal_id' => $sucursal->id,
                    'cantidad' => rand(10, 50),
                ]);
            }

            // Asignar bases a la sucursal
            foreach ($bases as $base) {
                Existencia::create([
                    'existenciable_id' => $base->id,
                    'existenciable_type' => Base::class,
                    'sucursal_id' => $sucursal->id,
                    'cantidad' => rand(10, 50),
                ]);
            }

            // Asignar tapas a la sucursal
            foreach ($tapas as $tapa) {
                Existencia::create([
                    'existenciable_id' => $tapa->id,
                    'existenciable_type' => Tapa::class,
                    'sucursal_id' => $sucursal->id,
                    'cantidad' => rand(10, 50),
                ]);
            }

            // Asignar productos a la sucursal
            foreach ($productos as $producto) {
                Existencia::create([
                    'existenciable_id' => $producto->id,
                    'existenciable_type' => Producto::class,
                    'sucursal_id' => $sucursal->id,
                    'cantidad' => rand(10, 50),
                ]);
            }

            // Asignar etiquetas a la sucursal
            foreach ($etiquetas as $etiqueta) {
                Existencia::create([
                    'existenciable_id' => $etiqueta->id,
                    'existenciable_type' => Etiqueta::class,
                    'sucursal_id' => $sucursal->id,
                    'cantidad' => rand(10, 50),
                ]);
            }

            // Asignar stock a la sucursal
            foreach ($stocks as $stock) {
                Existencia::create([
                    'existenciable_id' => $stock->id,
                    'existenciable_type' => Stock::class,
                    'sucursal_id' => $sucursal->id,
                    'cantidad' => rand(10, 50),
                ]);
            }




            // Asignacion de usuarios a la sucursal

            $adminUser = User::create([
                'email' => $sucursal->email, // Administrador
                'password' => bcrypt(12345678), // Administrador
                'rol_id' => 2, // Administrador
            ]);

            // Crear personal vinculado al usuario Administrador
            $adminPersonal = Personal::factory()->create([
                'user_id' => $adminUser->id,
            ]);

            // Registrar la relación Trabajo para el Administrador
            Trabajo::factory()->create([
                'sucursal_id' => $sucursal->id,
                'personal_id' => $adminPersonal->id,
                'fechaFinal' => null, // Siempre nulo
                'estado' => 1, // Activo
            ]);

            // Crear 3 usuarios Distribuidores
            $distribuidores = User::factory(3)->create([
                'rol_id' => 3, // Distribuidor
                'password' => bcrypt(12345678), // 
            ]);

            $distribuidores->each(function ($user) use ($sucursal) {
                // Crear personal vinculado al usuario
                $personal = Personal::factory()->create([
                    'user_id' => $user->id,
                ]);

                // Registrar Trabajo para cada Distribuidor
                Trabajo::factory()->create([
                    'sucursal_id' => $sucursal->id,
                    'personal_id' => $personal->id,
                    'fechaFinal' => null, // Siempre nulo
                    'estado' => 1, // Activo
                ]);
            });

            // Crear 2 usuarios Planta
            $plantas = User::factory(2)->create([
                'rol_id' => 4, // Planta
                'password' => bcrypt(12345678), // 
            ]);

            $plantas->each(function ($user) use ($sucursal) {
                // Crear personal vinculado al usuario
                $personal = Personal::factory()->create([
                    'user_id' => $user->id,
                ]);

                // Registrar Trabajo para cada Personal de Planta
                Trabajo::factory()->create([
                    'sucursal_id' => $sucursal->id,
                    'personal_id' => $personal->id,
                    'fechaFinal' => null, // Siempre nulo
                    'estado' => 1, // Activo
                ]);
            });
        }

        // Compra::factory(10)->create()->each(function ($compra) {
        //     // Obtener existencias aleatorias para vincularlas a los itemcompras
        //     $existencias = Existencia::inRandomOrder()->limit(rand(1, 3))->get();

        //     // Crear de 1 a 3 itemcompras por compra
        //     foreach ($existencias as $existencia) {
        //         Itemcompra::factory()->create([
        //             'compra_id' => $compra->id,
        //             'existencia_id' => $existencia->id,
        //         ]);
        //     }
        // });


        //Generar 10 ventas
        Venta::factory(10)->create([
            'personalEntrega_id' => null,
            'personal_id' => Personal::get()->random()->id
        ])->each(function ($venta) {
            // Generar entre 1 y 4 items por venta
            $items = Itemventa::factory(rand(1, 4))->create([
                'venta_id' => $venta->id,
                'existencia_id' => Existencia::inRandomOrder()->first()->id, // Asociar con una existencia aleatoria
            ]);

            // Calcular el total de los items
            $totalVenta = $items->sum(fn($item) => $item->cantidad * $item->precio);

            // Si estadoPago es 1 o 2 (completo o vendido), el pago es igual al total
            if ($venta->estadoPago == 1 || $venta->estadoPago == 2) {
                Pagoventa::factory()->create([
                    'venta_id' => $venta->id,
                    'monto' => $totalVenta,
                    'fechaPago' => now(),
                    // 'estado' => 1, // Pago completado
                ]);
            }
            // Si estadoPago es 0 (parcial), el pago es menor al total
            else {
                Pagoventa::factory()->create([
                    'venta_id' => $venta->id,
                    'monto' => $totalVenta * rand(50, 90) / 100, // Paga entre 50% y 90% del total
                    'fechaPago' => now(),
                    // 'estado' => 0, // Pago parcial
                ]);
            }
        });



        // Prestamo::factory(10)->create();

        Elaboracion::factory(10)->create();
        Embotellado::factory(10)->create();
        Etiquetado::factory(10)->create();
        // Reposicion::factory(10)->create();
        // Compra::factory(10)->create();
        // ItemCompra::factory(10)->create();

        // Trabajo::factory(10)->create();
        // Venta::factory(10)->create();
        // ItemVenta::factory(10)->create();
        // PagoVenta::factory(10)->create();
        // Distribucion::factory(10)->create();
        // Itemdistribucion::factory(10)->create();
        // Retorno::factory(10)->create();
    }
}
