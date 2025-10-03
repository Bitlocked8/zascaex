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



        foreach ($sucursales as $sucursal) {
            // Crear administrador de la sucursal
            $adminUser = User::create([
                'email' => $sucursal->email,
                'password' => bcrypt(12345678),
                'rol_id' => 2, // Administrador
            ]);

            $adminPersonal = Personal::factory()->create([
                'user_id' => $adminUser->id,
            ]);

            Trabajo::factory()->create([
                'sucursal_id' => $sucursal->id,
                'personal_id' => $adminPersonal->id,
                'labor_id' => null,
                'fechaFinal' => null,
                'estado' => 1,
            ]);

            // Crear 3 distribuidores
            $distribuidores = User::factory(3)->create([
                'rol_id' => 3,
                'password' => bcrypt(12345678),
            ]);

            $distribuidores->each(function ($user) use ($sucursal) {
                $personal = Personal::factory()->create(['user_id' => $user->id]);

                Trabajo::factory()->create([
                    'sucursal_id' => $sucursal->id,
                    'personal_id' => $personal->id,
                    'labor_id' => null,
                    'fechaFinal' => null,
                    'estado' => 1,
                ]);
            });

            // Crear 2 usuarios Planta
            $plantas = User::factory(2)->create([
                'rol_id' => 4,
                'password' => bcrypt(12345678),
            ]);

            $plantas->each(function ($user) use ($sucursal) {
                $personal = Personal::factory()->create(['user_id' => $user->id]);

                Trabajo::factory()->create([
                    'sucursal_id' => $sucursal->id,
                    'personal_id' => $personal->id,
                    'labor_id' => null,
                    'fechaFinal' => null,
                    'estado' => 1,
                ]);
            });
        }
       
    }
}
