<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Cliente as ModeloCliente;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class Cliente extends Component
{
    use WithFileUploads;
    use WithPagination;
    public $clienteId = null;
    public $nombre = '';
    public $empresa = '';
    public $razonSocial = '';
    public $nitCi = '';
    public $telefono = '';
    public $celular = '';
    public $direccion = '';
    public $ubicacion = '';
    public $departamento_localidad = '';
    public $establecimiento = '';
    public $disponible = '';
    public $movil = '';
    public $dias = '';
    public $bot = '';
    public $latitud = '';
    public $longitud = '';
    public $foto = null;
    public $estado = 1;
    public $categoria = 1;

    // 🔹 Campos del usuario asociado
    public $email = '';
    public $password = '';

    // 🔹 Campos auxiliares
    public $search = '';
    public $modal = false;
    public $detalleModal = false;
    public $accion = 'create';
    public $clienteSeleccionado = null;
    public $coordenadas;
    public $cantidad = 50;

    // 🔹 Alertas
    public $alertMessage = '';
    public $alertType = '';
    public $showAlert = false;

    protected $listeners = ['ocultarAlerta'];

    public function render()
    {
        $clientes = ModeloCliente::when($this->search, function ($query) {
            $query->where('nombre', 'like', '%' . $this->search . '%')
                ->orWhere('empresa', 'like', '%' . $this->search . '%')
                ->orWhere('nitCi', 'like', '%' . $this->search . '%')
                ->orWhere('telefono', 'like', '%' . $this->search . '%')
                ->orWhere('celular', 'like', '%' . $this->search . '%');
        })
            ->orderBy('id', 'desc')
            ->take($this->cantidad)
            ->get();

        return view('livewire.cliente', compact('clientes'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal($accion)
    {
        $this->resetCampos();
        $this->accion = $accion;
        $this->estado = 1;
        $this->categoria = 1;
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function editarCliente($id)
    {
        $cliente = ModeloCliente::findOrFail($id);
        $this->clienteId = $cliente->id;
        $this->nombre = $cliente->nombre;
        $this->empresa = $cliente->empresa;
        $this->razonSocial = $cliente->razonSocial;
        $this->nitCi = $cliente->nitCi;
        $this->telefono = $cliente->telefono;
        $this->celular = $cliente->celular;
        $this->direccion = $cliente->direccion;
        $this->ubicacion = $cliente->ubicacion;
        $this->departamento_localidad = $cliente->departamento_localidad;
        $this->establecimiento = $cliente->establecimiento;
        $this->disponible = $cliente->disponible;
        $this->movil = $cliente->movil;
        $this->dias = $cliente->dias;
        $this->bot = $cliente->bot;
        $this->latitud = $cliente->latitud;
        $this->longitud = $cliente->longitud;
        $this->foto = $cliente->foto;
        $this->estado = $cliente->estado;
        $this->categoria = $cliente->categoria;
        $this->email = $cliente->user->email ?? '';
        $this->password = '';
        $this->accion = 'edit';
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function verDetalle($id)
    {
        $this->clienteSeleccionado = ModeloCliente::findOrFail($id);
        $this->modal = false;
        $this->detalleModal = true;
    }

    public function guardarCliente()
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'razonSocial' => 'nullable|string|max:255',
            'nitCi' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'departamento_localidad' => 'nullable|string|max:255',
            'establecimiento' => 'nullable|string|max:255',
            'disponible' => 'nullable|string|max:100',
            'movil' => 'nullable|string|max:100',
            'dias' => 'nullable|string|max:255',
            'bot' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'foto' => $this->accion === 'edit' && !is_object($this->foto)
                ? 'nullable|string|max:255'
                : 'nullable|image|max:2048',
            'estado' => 'required|boolean',
            'categoria' => 'required|integer|min:1',
        ];

        if ($this->accion === 'create') {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|string|min:6';
        }

        $this->validate($rules);

        try {
            if ($this->accion === 'edit' && $this->clienteId) {
                $cliente = ModeloCliente::findOrFail($this->clienteId);

                // ✅ Actualizar usuario asociado
                if ($this->email && $cliente->user) {
                    $emailExiste = User::where('email', $this->email)
                        ->where('id', '!=', $cliente->user->id)
                        ->exists();
                    if ($emailExiste) {
                        return $this->mostrarAlerta('El email ya existe, intente con otro.', 'error');
                    }

                    $userData = ['email' => $this->email];
                    if ($this->password) {
                        $userData['password'] = bcrypt($this->password);
                    }
                    $cliente->user->update($userData);
                }

                // ✅ Manejo de imagen
                if (is_object($this->foto)) {
                    $rutaFoto = $this->foto->store('clientes', 'public');
                    if ($cliente->foto && Storage::disk('public')->exists($cliente->foto)) {
                        Storage::disk('public')->delete($cliente->foto);
                    }
                } else {
                    $rutaFoto = $this->foto;
                }

                // ✅ Actualizar datos
                $cliente->update([
                    'nombre' => $this->nombre,
                    'empresa' => $this->empresa,
                    'razonSocial' => $this->razonSocial,
                    'nitCi' => $this->nitCi,
                    'telefono' => $this->telefono,
                    'celular' => $this->celular,
                    'direccion' => $this->direccion,
                    'ubicacion' => $this->ubicacion,
                    'departamento_localidad' => $this->departamento_localidad,
                    'establecimiento' => $this->establecimiento,
                    'disponible' => $this->disponible,
                    'movil' => $this->movil,
                    'dias' => $this->dias,
                    'bot' => $this->bot,
                    'latitud' => $this->latitud,
                    'longitud' => $this->longitud,
                    'foto' => $rutaFoto,
                    'estado' => $this->estado,
                    'categoria' => $this->categoria,
                ]);

                $this->mostrarAlerta('Cliente actualizado con éxito.', 'success');
            }

            if ($this->accion === 'create') {
                // 👉 Aquí puedes agregar la lógica de creación de cliente y usuario si la usas
            }

            $this->cerrarModal();

        } catch (\Exception $e) {
            $this->mostrarAlerta('Error: ' . $e->getMessage(), 'error');
        }
    }

    public function separarCoordenadas()
    {
        if ($this->coordenadas) {
            $coords = explode(',', $this->coordenadas);
            if (count($coords) === 2) {
                $this->latitud = trim($coords[0]);
                $this->longitud = trim($coords[1]);
            }
        }
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->detalleModal = false;
        $this->resetCampos();
        $this->resetErrorBag();
    }

    private function resetCampos()
    {
        $this->reset([
            'clienteId',
            'nombre',
            'empresa',
            'razonSocial',
            'nitCi',
            'telefono',
            'celular',
            'direccion',
            'ubicacion',
            'departamento_localidad',
            'establecimiento',
            'disponible',
            'movil',
            'dias',
            'bot',
            'latitud',
            'longitud',
            'foto',
            'estado',
            'categoria',
            'email',
            'password',
            'clienteSeleccionado',
        ]);
    }

    public function mostrarAlerta($mensaje, $tipo = 'success')
    {
        $this->alertMessage = $mensaje;
        $this->alertType = $tipo;
        $this->showAlert = true;
        $this->dispatch('hide-alert');
    }

    public function ocultarAlerta()
    {
        $this->showAlert = false;
    }
}
