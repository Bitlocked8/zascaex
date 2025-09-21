<?php

namespace App\Livewire;

use App\Models\Elaboracion as ModelElaboracion;
use App\Models\Personal;
use App\Models\Existencia;
use Livewire\Component;
use App\Models\Preforma;
use App\Models\Base;

class Elaboracion extends Component
{
    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $preformas = [];
    public $bases = [];

    public $elaboracion_id = null;
    public $existencia_entrada_id = '';
    public $existencia_salida_id = '';
    public $personal_id = '';
    public $cantidad_entrada = 0;
    public $cantidad_salida = 0;
    public $fecha_elaboracion = '';
    public $merma = 0;
    public $observaciones = '';
    public $accion = 'create';
    public $elaboracionSeleccionada = null;

    public $existencias = [];
    public $personals = [];

    protected $rules = [
        'existencia_entrada_id' => 'required|exists:existencias,id',
        'existencia_salida_id' => 'nullable|exists:existencias,id',
        'personal_id' => 'required|exists:personals,id',
        'cantidad_entrada' => 'required|integer|min:1',
        'cantidad_salida' => 'nullable|integer|min:0',
        'fecha_elaboracion' => 'required|date',
        'merma' => 'nullable|integer|min:0',
        'observaciones' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->preformas = Existencia::with('existenciable')
            ->where('existenciable_type', Preforma::class)
            ->get();

        $this->bases = Existencia::with('existenciable')
            ->where('existenciable_type', Base::class)
            ->get();

        $this->personals = Personal::all();
    }


    public function render()
    {
        $elaboraciones = ModelElaboracion::with(['existenciaEntrada.existenciable', 'existenciaSalida.existenciable', 'personal'])
            ->when($this->search, function ($query) {
                $query->where('observaciones', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('livewire.elaboracion', compact('elaboraciones'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'existencia_entrada_id',
            'existencia_salida_id',
            'personal_id',
            'cantidad_entrada',
            'cantidad_salida',
            'fecha_elaboracion',
            'merma',
            'observaciones'
        ]);
        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $elaboracion = ModelElaboracion::findOrFail($id);

        $this->elaboracion_id = $elaboracion->id;
        $this->existencia_entrada_id = $elaboracion->existencia_entrada_id;
        $this->existencia_salida_id = $elaboracion->existencia_salida_id;
        $this->personal_id = $elaboracion->personal_id;
        $this->cantidad_entrada = $elaboracion->cantidad_entrada;
        $this->cantidad_salida = $elaboracion->cantidad_salida;
        $this->fecha_elaboracion = $elaboracion->fecha_elaboracion;
        $this->merma = $elaboracion->merma;
        $this->observaciones = $elaboracion->observaciones;

        $this->elaboracionSeleccionada = $elaboracion;
        $this->accion = 'edit';
    }

    public function guardar()
    {
        $this->validate();

        $elaboracion = ModelElaboracion::updateOrCreate(['id' => $this->elaboracion_id], [
            'existencia_entrada_id' => $this->existencia_entrada_id,
            'existencia_salida_id' => $this->existencia_salida_id ?: null,
            'personal_id' => $this->personal_id,
            'cantidad_entrada' => $this->cantidad_entrada,
            'cantidad_salida' => $this->cantidad_salida ?: 0,
            'fecha_elaboracion' => $this->fecha_elaboracion,
            'merma' => $this->merma,
            'observaciones' => $this->observaciones,
        ]);

        if ($this->accion === 'create') {
            $entrada = Existencia::find($this->existencia_entrada_id);
            $entrada->cantidad -= $this->cantidad_entrada;
            $entrada->save();

            if ($this->existencia_salida_id) {
                $salida = Existencia::find($this->existencia_salida_id);
                $salida->cantidad += $this->cantidad_salida;
                $salida->save();
            }
        }

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'existencia_entrada_id',
            'existencia_salida_id',
            'personal_id',
            'cantidad_entrada',
            'cantidad_salida',
            'fecha_elaboracion',
            'merma',
            'observaciones',
            'elaboracion_id',
            'elaboracionSeleccionada'
        ]);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $this->elaboracionSeleccionada = ModelElaboracion::with(['existenciaEntrada.existenciable', 'existenciaSalida.existenciable', 'personal'])->findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->elaboracionSeleccionada = null;
    }
}
