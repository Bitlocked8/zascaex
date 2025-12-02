<?php

namespace App\Livewire;

use App\Models\Trabajo;
use App\Models\Sucursal;
use App\Models\Personal;
use Livewire\Component;

class Trabajador extends Component
{
    public $fechaInicio = '', $fechaFinal = '', $estado = 1;
    public $sucursal_id = null, $personal_id = null;
    public $trabajo_id = null;
    public $modal = false;
    public $modalDetalle = false;
    public $trabajoSeleccionado = null;
    public $accion = 'create';
    public $labor_id = null;
    public $labores = [];
    public $search = '';
    public $modalLabores = false;

    public function render()
    {
        $trabajos = Trabajo::with(['sucursal', 'personal'])
            ->when($this->search, function ($query) {
                $query->whereHas('personal', function ($q) {
                    $q->where('nombres', 'like', '%' . $this->search . '%')
                        ->orWhere('apellidos', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();

        $sucursales = Sucursal::all();
        $personales = Personal::all();

        return view('livewire.trabajador', compact('trabajos', 'sucursales', 'personales'));
    }


    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['trabajo_id', 'fechaInicio', 'fechaFinal', 'estado', 'sucursal_id', 'personal_id']);
        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        } elseif ($accion === 'create') {
            $this->fechaInicio = now();
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $trabajo = Trabajo::findOrFail($id);
        $this->trabajo_id = $trabajo->id;
        $this->fechaInicio = $trabajo->fechaInicio;
        $this->fechaFinal = $trabajo->fechaFinal;
        $this->estado = $trabajo->estado;
        $this->sucursal_id = $trabajo->sucursal_id;
        $this->personal_id = $trabajo->personal_id;
        $this->labor_id = $trabajo->labor_id;
        $this->accion = 'edit';
    }

    public function guardar()
    {
        $this->validate([
            'estado' => 'required|boolean',
            'sucursal_id' => 'required|exists:sucursals,id',
            'personal_id' => 'required|exists:personals,id',
            'labor_id' => 'nullable|exists:labors,id',
        ]);

        if (!$this->trabajo_id) {
            $this->fechaInicio = now();
        }

        $fechaFinal = $this->fechaFinal ?: null;

        Trabajo::updateOrCreate(
            ['id' => $this->trabajo_id],
            [
                'fechaInicio' => $this->fechaInicio,
                'fechaFinal' => $fechaFinal,
                'estado' => $this->estado,
                'sucursal_id' => $this->sucursal_id,
                'personal_id' => $this->personal_id,
                'labor_id' => $this->labor_id,
            ]
        );

        session()->flash('message', $this->trabajo_id ? 'Trabajo actualizado con éxito.' : 'Trabajo creado con éxito.');
        $this->cerrarModal();
    }

    public function abrirModalLabores()
    {
        $this->labores = \App\Models\Labor::all()->map(function ($l) {
            return [
                'id' => $l->id,
                'nombre' => $l->nombre,
                'descripcion' => $l->descripcion,
                'estado' => $l->estado,
            ];
        })->toArray();

        $this->modalLabores = true;
    }

    public function agregarLabor()
    {
        $this->labores[] = [
            'id' => null,
            'nombre' => '',
            'descripcion' => '',
            'estado' => 1,
        ];
    }

    public function eliminarLabor($index)
    {
        $labor = $this->labores[$index] ?? null;
        if ($labor && isset($labor['id']) && $labor['id']) {
            \App\Models\Labor::find($labor['id'])?->delete();
        }
        unset($this->labores[$index]);
        $this->labores = array_values($this->labores);
    }

    public function guardarLabores()
    {
        foreach ($this->labores as $labor) {
            \App\Models\Labor::updateOrCreate(
                ['id' => $labor['id'] ?? 0],
                [
                    'nombre' => $labor['nombre'],
                    'descripcion' => $labor['descripcion'],
                    'estado' => $labor['estado'],
                ]
            );
        }
        $this->modalLabores = false;
        $this->labores = [];
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['fechaInicio', 'fechaFinal', 'estado', 'sucursal_id', 'personal_id', 'trabajo_id', 'labor_id']);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $this->trabajoSeleccionado = Trabajo::findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->trabajoSeleccionado = null;
    }
}
