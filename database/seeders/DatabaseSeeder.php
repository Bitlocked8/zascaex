<?php

namespace Database\Seeders;

use App\Models\Embotellado;
use App\Models\Asignacion;
use App\Models\Base;
use App\Models\Coche;
use App\Models\Distribucion;
use App\Models\Empresa;
use App\Models\Personal;
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
use App\Models\Promo;
use App\Models\ItemPromo;
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
        // Crear clientes y promos
        $clientes = Cliente::factory(10)->create();
        $promos   = Promo::factory(5)->create();

        // Asignar aleatoriamente 1 a 3 promos por cliente usando ItemPromo
        $clientes->each(function ($cliente) use ($promos) {
            $clientePromos = $promos->random(rand(1, 3));

            foreach ($clientePromos as $promo) {
                ItemPromo::factory()->create([
                    'cliente_id'       => $cliente->id,
                    'promo_id'         => $promo->id,
                    'codigo'           => strtoupper(uniqid('PROMO-')), // ejemplo de código único
                    'fecha_asignacion' => now(),
                ]);
            }
        });
        $codigo = strtoupper(uniqid('PROMO-')); // Código aleatorio único
        $clientes = Cliente::all();              // Todos los clientes (o un subset)
        $promos   = Promo::all();                // Todas las promociones (o un subset)

        foreach ($clientes as $cliente) {
            foreach ($promos as $promo) {

                // Verificar que el cliente NO tenga ya esta promo
                $existe = ItemPromo::where('cliente_id', $cliente->id)
                    ->where('promo_id', $promo->id)
                    ->exists();

                if (!$existe) {
                    ItemPromo::create([
                        'cliente_id'       => $cliente->id,
                        'promo_id'         => $promo->id,
                        'codigo'           => $codigo,
                        'fecha_asignacion' => now(),
                    ]);
                }
            }
        }


        // $this->call(ClienteSeeder::class);
        Proveedor::factory(10)->create();
        $empresa = Empresa::factory(1)->create();
        Sucursal::create(['nombre' => 'Cochabamba Central', 'direccion' => 'Av. Heroínas 123, Cochabamba, Bolivia', 'telefono' => '591 4 4251234', 'zona' => 'Centro', 'empresa_id' => 1]);
        Sucursal::create(['nombre' => 'Santa Cruz Norte', 'direccion' => 'Av. Banzer 456, Santa Cruz, Bolivia', 'telefono' => '591 3 3435678', 'zona' => 'Norte', 'empresa_id' => 1]);
        $sucursales = Sucursal::all();
        $emailSucursal = ['cochabamba@mail.com', 'santacruzmail.com'];
        foreach ($sucursales as $index => $sucursal) {
            $sucursal->email = $emailSucursal[$index] ?? null;
        }


        Preforma::factory()->count(5)->create();
        Base::factory()->count(5)->create();

        Tapa::factory()->count(5)->create();

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

        Producto::factory()->count(5)->create();


        $preformas = Preforma::get();
        $bases = Base::get();
        $tapas = Tapa::get();
        $productos = Producto::get();
        $etiquetas = Etiqueta::get();



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
        Elaboracion::factory(10)->create();
        Embotellado::factory(10)->create();
        Etiquetado::factory(10)->create();
   
    }
}
