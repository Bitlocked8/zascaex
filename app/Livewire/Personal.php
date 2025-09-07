<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Personal as ModelPersonal;
use App\Models\User;
use App\Models\Rol;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Added DB facade import

class Personal extends Component
{
    use WithPagination;

    public $search = '';
    public $modal = false;
    public $detalleModal = false;
    public $accion = 'create';
    public $personalId = null;

    // Personal fields
    public $nombres = '';
    public $apellidos = '';
    public $direccion = '';
    public $celular = '';
    public $estado = true;

    // User fields
    public $email = '';
    public $password = '';
    public $rol_id = '';

    public $personalSeleccionado = null;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'nombres' => 'required|string|max:255',
        'apellidos' => 'required|string|max:255',
        'direccion' => 'nullable|string|max:255',
        'celular' => 'required|string|max:15',
        'estado' => 'required|boolean',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|string|min:8',
        'rol_id' => 'required|exists:rols,id',
    ];

    public function mount()
    {
        // Initialize rules for edit to allow existing email and optional password
        $this->rules['email'] = 'required|email|max:255|unique:users,email,' . $this->personalId . ',personal_id';
        $this->rules['password'] = 'nullable|string|min:8';
    }

    public function render()
    {
        $personales = ModelPersonal::query()
            ->when($this->search, function ($query) {
                $query->where('nombres', 'like', '%' . $this->search . '%')
                    ->orWhere('apellidos', 'like', '%' . $this->search . '%')
                    ->orWhere('celular', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->with('user') // Eager load user relationship
            ->paginate(perPage: 4);

        $roles = Rol::all(); // Fetch roles for dropdown

        return view('livewire.personal', compact('personales', 'roles'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['nombres', 'apellidos', 'direccion', 'celular', 'estado', 'personalId', 'email', 'password', 'rol_id']);
        $this->accion = $accion;
        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }
        $this->modal = true;
    }

    public function editar($id)
    {
        $personal = ModelPersonal::with('user')->findOrFail($id);
        $this->personalId = $personal->id;
        $this->nombres = $personal->nombres;
        $this->apellidos = $personal->apellidos;
        $this->direccion = $personal->direccion;
        $this->celular = $personal->celular;
        $this->estado = $personal->estado;
        $this->email = $personal->user->email ?? '';
        $this->rol_id = $personal->user->rol_id ?? '';
        $this->password = ''; // Password is not loaded for security
        $this->accion = 'edit';
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function verDetalle($id)
    {
        $this->personalSeleccionado = ModelPersonal::with('user')->findOrFail($id);
        $this->modal = false;
        $this->detalleModal = true;
    }

    public function guardarPersonal()
    {
        if ($this->accion === 'edit') {
            // Validar solo campos personales, sin validar email ni password
            $this->validate([
                'nombres' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'direccion' => 'nullable|string|max:255',
                'celular' => 'required|string|max:15',
                'estado' => 'required|boolean',
                'rol_id' => 'required|exists:rols,id', // Si quieres validar rol_id siempre
            ]);
        } else {
            // Validar todo al crear, incluido email único y password obligatorio
            $this->validate([
                'nombres' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'direccion' => 'nullable|string|max:255',
                'celular' => 'required|string|max:15',
                'estado' => 'required|boolean',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'rol_id' => 'required|exists:rols,id',
            ]);
        }

        try {
            DB::beginTransaction();

            if ($this->accion === 'edit' && $this->personalId) {
                $personal = ModelPersonal::findOrFail($this->personalId);
                $personal->update([
                    'nombres' => $this->nombres,
                    'apellidos' => $this->apellidos,
                    'direccion' => $this->direccion,
                    'celular' => $this->celular,
                    'estado' => $this->estado,
                ]);

                $user = $personal->user;
                if ($user) {
                    $user->update([
                        'rol_id' => $this->rol_id,
                        'estado' => $this->estado,
                        // no cambiar email ni password
                    ]);
                }

                LivewireAlert::title('Personal actualizado con éxito.')->success()->show();
            } else {
                $personal = ModelPersonal::create([
                    'nombres' => $this->nombres,
                    'apellidos' => $this->apellidos,
                    'direccion' => $this->direccion,
                    'celular' => $this->celular,
                    'estado' => $this->estado,
                ]);

                $user = User::create([
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'rol_id' => $this->rol_id,
                    'estado' => $this->estado,
                ]);

                $personal->update(['user_id' => $user->id]);

                LivewireAlert::title('Personal y usuario registrados con éxito.')->success()->show();
            }

            DB::commit();
            $this->cerrarModal();
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Ocurrió un error: ' . $e->getMessage())->error()->show();
        }
    }


    public function cerrarModal()
    {
        $this->modal = false;
        $this->detalleModal = false;
        $this->reset(['nombres', 'apellidos', 'direccion', 'celular', 'estado', 'personalId', 'personalSeleccionado', 'email', 'password', 'rol_id']);
        $this->resetErrorBag();
    }
}
