<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Orden;
use Carbon\Carbon;

class Ordenes extends Component
{
    public $search = '';
    public $modal = false;
    public $detalleModal = false;

    public $orden_id = null;
    public $fecha;
    public $fecha_fin;
    public $detalle = '';
    public $cantidad_total;
    public $cantidad_preparada = 0;
    public $estado = 0;
    public $accion = 'create';
    public $mostrarCompletadas = false;

    public $confirmarEliminar = false;
    public $ordenEliminarId = null;
    public $mensaje = null;

    protected $rules = [
        'detalle' => 'nullable|string|max:1000',
        'cantidad_total' => 'required|integer|min:0',
        'cantidad_preparada' => 'nullable|integer|min:0',
    ];

    protected $messages = [
        'cantidad_total.required' => 'La cantidad total es obligatoria.',
        'cantidad_total.integer' => 'La cantidad total debe ser un número entero.',
        'cantidad_total.min' => 'La cantidad total no puede ser negativa.',
        'cantidad_preparada.integer' => 'La cantidad preparada debe ser un número entero.',
        'cantidad_preparada.min' => 'La cantidad preparada no puede ser negativa.',
    ];

    public function render()
    {
        $ordenes = Orden::query()
            ->when(!$this->mostrarCompletadas, fn ($q) => $q->where('estado', 0))
            ->when($this->search, fn ($q) => $q->where('detalle', 'like', "%{$this->search}%"))
            ->orderBy('fecha', 'desc')
            ->get();

        return view('livewire.ordenes', compact('ordenes'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->resetCampos();
        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        } else {
            $this->fecha = Carbon::now();
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $orden = Orden::findOrFail($id);

        $this->orden_id = $orden->id;
        $this->fecha = $orden->fecha;
        $this->fecha_fin = $orden->fecha_fin;
        $this->detalle = $orden->detalle;
        $this->cantidad_total = $orden->cantidad_total;
        $this->cantidad_preparada = $orden->cantidad_preparada;
        $this->estado = $orden->estado;
        $this->accion = 'edit';
    }

    public function guardar()
    {
        $this->validate();

        $orden = Orden::find($this->orden_id);
        $estadoAnterior = $orden?->estado;

        Orden::updateOrCreate(
            ['id' => $this->orden_id],
            [
                'fecha' => $this->orden_id ? $this->fecha : Carbon::now(),
                'detalle' => $this->detalle,
                'cantidad_total' => $this->cantidad_total,
                'cantidad_preparada' => $this->cantidad_preparada,
                'estado' => $this->estado,
                'fecha_fin' => (
                    $this->estado == 1 &&
                    ($estadoAnterior === null || $estadoAnterior == 0)
                ) ? Carbon::now() : $this->fecha_fin,
            ]
        );

        $this->mensaje = 'Orden guardada correctamente';
        $this->cerrarModal();
    }

    public function confirmarEliminacion($id)
    {
        $this->ordenEliminarId = $id;
        $this->confirmarEliminar = true;
    }

    public function cancelarEliminacion()
    {
        $this->confirmarEliminar = false;
        $this->ordenEliminarId = null;
    }

    public function eliminarConfirmado()
    {
        Orden::findOrFail($this->ordenEliminarId)->delete();
        $this->mensaje = 'Orden eliminada correctamente';
        $this->cancelarEliminacion();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->resetCampos();
    }

    public function abrirDetalle($id)
    {
        $orden = Orden::findOrFail($id);

        $this->orden_id = $orden->id;
        $this->fecha = $orden->fecha;
        $this->fecha_fin = $orden->fecha_fin;
        $this->detalle = $orden->detalle;
        $this->cantidad_total = $orden->cantidad_total;
        $this->cantidad_preparada = $orden->cantidad_preparada;
        $this->estado = $orden->estado;

        $this->detalleModal = true;
    }

    public function cerrarDetalle()
    {
        $this->detalleModal = false;
        $this->resetCampos();
    }

    private function resetCampos()
    {
        $this->reset([
            'orden_id',
            'fecha',
            'fecha_fin',
            'detalle',
            'cantidad_total',
            'cantidad_preparada',
            'estado'
        ]);
        $this->resetErrorBag();
    }
}