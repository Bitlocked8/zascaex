<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Personal as ModelPersonal;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Personal extends Component
{
    public $search = '';
    public $modal = false;
    public $detalleModal = false;
    public $accion = 'create';
    public $personalId = null;

    public $nombres = '';
    public $apellidos = '';
    public $direccion = '';
    public $celular = '';
    public $estado = true;

    public $email = '';
    public $password = '';
    public $rol_id = '';

    public $personalSeleccionado = null;

    public function render()
    {
        return view('livewire.personal', [
            'personales' => ModelPersonal::with('user')
                ->when($this->search, function ($q) {
                    $q->where('nombres', 'like', "%{$this->search}%")
                      ->orWhere('apellidos', 'like', "%{$this->search}%")
                      ->orWhere('celular', 'like', "%{$this->search}%");
                })
                ->orderByDesc('id')
                ->get(),
            'roles' => Rol::all()
        ]);
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset();
        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $this->personalSeleccionado = ModelPersonal::with('user')->findOrFail($id);

        $this->personalId = $id;
        $this->nombres = $this->personalSeleccionado->nombres;
        $this->apellidos = $this->personalSeleccionado->apellidos;
        $this->direccion = $this->personalSeleccionado->direccion;
        $this->celular = $this->personalSeleccionado->celular;
        $this->estado = $this->personalSeleccionado->estado;
        $this->email = $this->personalSeleccionado->user->email;
        $this->rol_id = $this->personalSeleccionado->user->rol_id;
        $this->password = '';
    }

    public function guardarPersonal()
    {
        $rules = [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'celular' => 'nullable|string|max:15',
            'estado' => 'boolean',
            'rol_id' => 'required|exists:rols,id',
            'email' => 'required|string|max:255|regex:/^[A-Za-z0-9]+$/'
        ];

        if ($this->accion === 'create') {
            $rules['email'] .= '|unique:users,email';
            $rules['password'] = 'required|string|min:8';
        } else {
            $rules['email'] .= '|unique:users,email,' . $this->personalSeleccionado->user->id;
            if ($this->password) {
                $rules['password'] = 'min:8';
            }
        }

        $this->validate($rules);

        DB::beginTransaction();

        try {
            if ($this->accion === 'edit') {

                $this->personalSeleccionado->update([
                    'nombres' => $this->nombres,
                    'apellidos' => $this->apellidos,
                    'direccion' => $this->direccion,
                    'celular' => $this->celular,
                    'estado' => $this->estado,
                ]);

                $dataUser = [
                    'email' => $this->email,
                    'rol_id' => $this->rol_id,
                    'estado' => $this->estado,
                ];

                if ($this->password) {
                    $dataUser['password'] = Hash::make($this->password);
                }

                $this->personalSeleccionado->user->update($dataUser);

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
            }

            DB::commit();
            $this->cerrarModal();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cerrarModal()
    {
        $this->reset();
        $this->resetErrorBag();
    }
}
