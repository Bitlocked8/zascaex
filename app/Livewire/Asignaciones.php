<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignado;
use App\Models\Existencia;
use App\Models\Personal;
use Illuminate\Support\Facades\Validator;

class Asignaciones extends Component
{
    public $searchCodigo = '';
    public $modal = false;
    public $accion = 'create';
    public $asignacion_id;
    public $codigo;
    public $existencia_id;
    public $personal_id;
    public $cantidad;
    public $fecha;
    public $motivo;
    public $observaciones;
    public $existencias = [];
    public $personales = [];

    // manejo de modal de errores
    public $modalError = false;
    public $mensajeError = '';

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'asignacion_id',
            'existencia_id',
            'personal_id',
            'cantidad',
            'fecha',
            'observaciones',
            'motivo',
            'codigo'
        ]);
        $this->accion = $accion;
        $this->fecha = now()->format('Y-m-d');

        $this->existencias = Existencia::with('existenciable', 'sucursal')
            ->where('cantidad', '>', 0)
            ->get();

        $this->personales = Personal::all();

        if ($accion === 'create') {
            $this->codigo = 'A-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        }

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $asignado = Asignado::findOrFail($id);
        $this->asignacion_id = $asignado->id;
        $this->codigo = $asignado->codigo;
        $this->existencia_id = $asignado->existencia_id;
        $this->personal_id = $asignado->personal_id;
        $this->cantidad = $asignado->cantidad;
        $this->fecha = $asignado->fecha;
        $this->motivo = $asignado->motivo;
        $this->observaciones = $asignado->observaciones;
    }

    public function guardarAsignacion()
    {
   
        $validator = Validator::make([
            'existencia_id' => $this->existencia_id,
            'personal_id' => $this->personal_id,
            'cantidad' => $this->cantidad,
            'fecha' => $this->fecha,
            'motivo' => $this->motivo,
            'observaciones' => $this->observaciones,
        ], [
            'existencia_id' => 'required|exists:existencias,id',
            'personal_id' => 'required|exists:personals,id',
            'cantidad' => 'required|integer|min:1',
            'fecha' => 'required|date',
            'motivo' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:500',
        ], [
            'existencia_id.required' => 'Debe seleccionar un producto.',
            'existencia_id.exists' => 'El producto seleccionado no existe.',
            'personal_id.required' => 'Debe seleccionar un personal.',
            'personal_id.exists' => 'El personal seleccionado no existe.',
            'cantidad.required' => 'Debe ingresar una cantidad.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad mínima es 1.',
            'fecha.required' => 'Debe ingresar la fecha.',
            'fecha.date' => 'La fecha no es válida.',
            'motivo.max' => 'El motivo no puede exceder 255 caracteres.',
            'observaciones.max' => 'Las observaciones no pueden exceder 500 caracteres.',
        ]);

        if ($validator->fails()) {
            $this->mensajeError = implode("\n", $validator->errors()->all());
            $this->modalError = true;
            return;
        }

        $existencia = Existencia::findOrFail($this->existencia_id);

        if ($this->accion === 'create') {
            if ($this->cantidad > $existencia->cantidad) {
                $this->mensajeError = "La cantidad solicitada supera el stock disponible ({$existencia->cantidad}).";
                $this->modalError = true;
                return;
            }

            Asignado::create([
                'codigo' => $this->codigo,
                'existencia_id' => $this->existencia_id,
                'personal_id' => $this->personal_id,
                'cantidad' => $this->cantidad,
                'fecha' => $this->fecha,
                'motivo' => $this->motivo,
                'observaciones' => $this->observaciones,
            ]);

            $existencia->cantidad -= $this->cantidad;
            $existencia->save();
        } elseif ($this->accion === 'edit' && $this->asignacion_id) {
            $asignado = Asignado::findOrFail($this->asignacion_id);
            $diferencia = $this->cantidad - $asignado->cantidad;

            if ($diferencia > 0 && $diferencia > $existencia->cantidad) {
                $this->mensajeError = "No puedes aumentar la asignación, stock insuficiente ({$existencia->cantidad}).";
                $this->modalError = true;
                return;
            }

            $existencia->cantidad -= $diferencia;
            $existencia->save();

            $asignado->update([
                'existencia_id' => $this->existencia_id,
                'personal_id' => $this->personal_id,
                'cantidad' => $this->cantidad,
                'fecha' => $this->fecha,
                'motivo' => $this->motivo,
                'observaciones' => $this->observaciones,
            ]);
        }

        $this->modal = false;
        $this->reset([
            'asignacion_id',
            'codigo',
            'existencia_id',
            'personal_id',
            'cantidad',
            'fecha',
            'motivo',
            'observaciones'
        ]);
        session()->flash('message', 'Asignación guardada correctamente!');
    }


    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'asignacion_id',
            'codigo',
            'existencia_id',
            'personal_id',
            'cantidad',
            'fecha',
            'motivo',
            'observaciones'
        ]);
    }

    public function render()
    {
        return view('livewire.asignaciones', [
            'asignaciones' => Asignado::with('existencia.existenciable', 'personal')
                ->when($this->searchCodigo, fn($q) => $q->where('codigo', 'like', '%' . $this->searchCodigo . '%'))
                ->latest()->get(),
            'personal' => Personal::whereHas('trabajos', fn($q) => $q->where('estado', 1))
                ->with(['trabajos' => fn($q) => $q->where('estado', 1)->latest('fechaInicio')->with('sucursal')])
                ->get(),
        ]);
    }
}
