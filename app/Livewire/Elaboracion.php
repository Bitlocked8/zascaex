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
    public $sucursalSeleccionada = null;
    public $existencias = [];
    public $personals = [];
    public $cantEntradaEditable = true;
    public $codigo = '';
    public $estado = 'pendiente';

    protected $rules = [
        'existencia_entrada_id' => 'required|exists:existencias,id',
        'existencia_salida_id' => 'nullable|exists:existencias,id',
        'personal_id' => 'required|exists:personals,id',
        'cantidad_entrada' => 'required|integer|min:1',
        'cantidad_salida' => 'nullable|integer|min:0',
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

    public function filtrarPorSucursal($sucursalId)
    {
        $this->sucursalSeleccionada = $sucursalId;

        $this->preformas = Existencia::with('existenciable')
            ->where('existenciable_type', Preforma::class)
            ->where('sucursal_id', $sucursalId)
            ->get();

        $this->bases = Existencia::with('existenciable')
            ->where('existenciable_type', Base::class)
            ->where('sucursal_id', $sucursalId)
            ->get();
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
            'merma',
            'observaciones',
            'codigo',
            'estado',
            'fecha_elaboracion',
            'sucursalSeleccionada',
        ]);

        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
            if ($this->elaboracionSeleccionada->cantidad_salida > 0) {
                $this->cantEntradaEditable = false;
            } else {
                $this->cantEntradaEditable = true;
            }
        } else {
            $this->fecha_elaboracion = date('Y-m-d');
            $ultimoId = ModelElaboracion::max('id') ?? 0;
            $this->codigo = 'L-' . date('Ymd') . '-' . ($ultimoId + 1);
            $this->estado = 'pendiente';
            $this->cantEntradaEditable = true;
        }


        $this->modal = true;
    }


    public function editar($id)
    {
        $elaboracion = ModelElaboracion::with('existenciaEntrada')->findOrFail($id);

        $this->elaboracion_id = $elaboracion->id;
        $this->existencia_entrada_id = $elaboracion->existencia_entrada_id;
        $this->existencia_salida_id = $elaboracion->existencia_salida_id;
        $this->personal_id = $elaboracion->personal_id;
        $this->cantidad_entrada = $elaboracion->cantidad_entrada;
        $this->cantidad_salida = $elaboracion->cantidad_salida;
        $this->fecha_elaboracion = $elaboracion->fecha_elaboracion;
        $this->merma = $elaboracion->merma;
        $this->observaciones = $elaboracion->observaciones;
        $this->codigo = $elaboracion->codigo;
        $this->estado = $elaboracion->estado;
        $this->elaboracionSeleccionada = $elaboracion;
        $this->accion = 'edit';
        $this->existencia_entrada_id = (int) $elaboracion->existencia_entrada_id;
        $this->existencia_salida_id = (int) $elaboracion->existencia_salida_id;
        $this->sucursalSeleccionada = $elaboracion->existenciaEntrada->sucursal_id ?? null;
    }


    public function guardar()
    {
        $this->validate();

        $entrada = Existencia::find($this->existencia_entrada_id);
        if ($this->accion === 'create' && $this->cantidad_entrada > $entrada->cantidad) {
            $this->addError('cantidad_entrada', 'No puedes usar más de lo disponible en stock (' . $entrada->cantidad . ').');
            return;
        }
        if ($this->cantidad_salida > $this->cantidad_entrada) {
            $this->addError('cantidad_salida', 'La cantidad de salida no puede ser mayor que la entrada.');
            return;
        }
        $this->merma = $this->cantidad_entrada - $this->cantidad_salida;
        if ($this->accion === 'create') {
            $ultimoId = ModelElaboracion::max('id') ?? 0;
            $this->codigo = 'L-' . date('Ymd') . '-' . ($ultimoId + 1);
            $this->estado = 'pendiente';
            $this->fecha_elaboracion = date('Y-m-d');
            $elaboracion = ModelElaboracion::create([
                'codigo' => $this->codigo,
                'estado' => $this->estado,
                'existencia_entrada_id' => $this->existencia_entrada_id,
                'existencia_salida_id' => $this->existencia_salida_id ?: null,
                'personal_id' => $this->personal_id,
                'cantidad_entrada' => $this->cantidad_entrada,
                'cantidad_salida' => $this->cantidad_salida ?: 0,
                'fecha_elaboracion' => $this->fecha_elaboracion,
                'merma' => $this->merma,
                'observaciones' => $this->observaciones,
            ]);
            $entrada->decrement('cantidad', $this->cantidad_entrada);
            if ($this->existencia_salida_id && $this->cantidad_salida > 0) {
                $salida = Existencia::find($this->existencia_salida_id);
                $salida->increment('cantidad', $this->cantidad_salida);
            }
        } else {
            $diferenciaEntrada = $this->cantidad_entrada - $this->elaboracionSeleccionada->cantidad_entrada;

            if ($diferenciaEntrada > 0 && $diferenciaEntrada > $entrada->cantidad) {
                $this->addError('cantidad_entrada', 'No puedes usar más de lo disponible en stock (' . $entrada->cantidad . ').');
                return;
            }
            if ($diferenciaEntrada > 0) {
                $entrada->decrement('cantidad', $diferenciaEntrada);
            } elseif ($diferenciaEntrada < 0) {
                $entrada->increment('cantidad', abs($diferenciaEntrada));
            }
            if ($this->existencia_salida_id) {
                $salida = Existencia::find($this->existencia_salida_id);
                $diferenciaSalida = $this->cantidad_salida - $this->elaboracionSeleccionada->cantidad_salida;

                if ($diferenciaSalida > 0) {
                    $salida->increment('cantidad', $diferenciaSalida);
                } elseif ($diferenciaSalida < 0) {
                    $salida->decrement('cantidad', abs($diferenciaSalida));
                }
            }
            $this->elaboracionSeleccionada->update([
                'existencia_entrada_id' => $this->existencia_entrada_id,
                'existencia_salida_id' => $this->existencia_salida_id ?: null,
                'personal_id' => $this->personal_id,
                'cantidad_entrada' => $this->cantidad_entrada,
                'cantidad_salida' => $this->cantidad_salida ?: 0,
                'fecha_elaboracion' => $this->fecha_elaboracion,
                'merma' => $this->merma,
                'observaciones' => $this->observaciones,
                'estado' => $this->estado,
            ]);
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
            'codigo',
            'estado',
            'elaboracion_id',
            'elaboracionSeleccionada'
        ]);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $this->elaboracionSeleccionada = ModelElaboracion::with([
            'existenciaEntrada.existenciable',
            'existenciaSalida.existenciable',
            'personal'
        ])->findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->elaboracionSeleccionada = null;
    }
}
