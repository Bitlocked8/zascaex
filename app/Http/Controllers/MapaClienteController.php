<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class MapaClienteController extends Controller
{
    /**
     * Muestra clientes con coordenadas registradas en un mapa.
     */
    public function mostrar()
    {
        $clientes = Cliente::whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->paginate(5);

        return view('clientes.mapa', compact('clientes'));
    }

    /**
     * Muestra el formulario para registrar un nuevo cliente con mapa.
     */
    public function mostrarFormularioMapa()
    {
        return view('clientes.registrar');
    }

    /**
     * Lista todos los clientes paginados.
     */
    public function index()
    {
        $clientes = Cliente::paginate(5);
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Registra un nuevo cliente y su usuario asociado.
     */
    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'razonSocial' => 'nullable|string|max:255',
            'nitCi' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'establecimiento' => 'nullable|string|max:255',
            'disponible' => 'nullable|string|max:255',
            'bot' => 'nullable|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'movil' => 'nullable|string|max:50',
            'dias' => 'nullable|string|max:255',
            'departamento_localidad' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'foto' => 'nullable|image|max:4096',
            'estado' => 'required|boolean',
            'categoria' => 'required|integer|in:1,2,3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];

        $validated = $request->validate($rules);

        try {
            // 📷 Subir foto si existe
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('clientes', 'public');
            }

            // 👤 Crear usuario asociado
            $user = User::create([
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'rol_id' => 5,
                'estado' => 1,
            ]);

            // 🔢 Generar código único para el cliente
            $ultimoCliente = Cliente::latest('id')->first();
            $codigo = 'C-' . str_pad(($ultimoCliente->id ?? 0) + 1, 4, '0', STR_PAD_LEFT);

            // 🧾 Crear cliente
            $cliente = Cliente::create([
                'codigo' => $codigo,
                'nombre' => $validated['nombre'],
                'empresa' => $validated['empresa'] ?? null,
                'razonSocial' => $validated['razonSocial'] ?? null,
                'nitCi' => $validated['nitCi'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'celular' => $validated['celular'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'establecimiento' => $validated['establecimiento'] ?? null,
                'disponible' => $validated['disponible'] ?? null,
                'bot' => $validated['bot'] ?? null,
                'ubicacion' => $validated['ubicacion'] ?? null,
                'movil' => $validated['movil'] ?? null,
                'dias' => $validated['dias'] ?? null,
                'departamento_localidad' => $validated['departamento_localidad'] ?? null,
                'latitud' => $validated['latitud'] ?? null,
                'longitud' => $validated['longitud'] ?? null,
                'foto' => $fotoPath,
                'estado' => $validated['estado'],
                'categoria' => $validated['categoria'],
                'user_id' => $user->id,
            ]);

            return Redirect::route('home')->with('success', "Cliente registrado con éxito. Código: $codigo");

        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Error al registrar el cliente: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Actualiza coordenadas (latitud/longitud) de un cliente.
     */
    public function actualizarCoordenadas(Request $request, $id)
    {
        $validated = $request->validate([
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
        ]);

        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->latitud = $validated['latitud'];
            $cliente->longitud = $validated['longitud'];
            $cliente->save();

            return response()->json([
                'success' => true,
                'message' => 'Coordenadas actualizadas correctamente.',
                'cliente' => $cliente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar coordenadas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muestra formulario de edición de cliente.
     */
    public function editar($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.editar', compact('cliente'));
    }

    /**
     * Muestra la vista de un cliente individual en el mapa.
     */
    public function showMapClient(Request $request)
    {
        $cliente = Cliente::findOrFail($request->id);
        return view('clientes.mapaCliente', compact('cliente'));
    }
}
