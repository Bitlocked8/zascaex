<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Existencia;
use App\Models\Reposicion;
use App\Models\Sucursal;
use App\Models\Proveedor;
use App\Models\Personal;
use Carbon\Carbon;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class Stocks extends Component
{
    use WithFileUploads;
    public $estado_revision = 0;

    public $searchCodigo = '';
    public $ultimaReposicion = null;
    public $modal = false;
    public $reposicionSeleccionada = null;
    public $modalConfigGlobal = false;
    public $codigo;
    public $reposicion_id = null;
    public $existencia_id;
    public $personal_id;
    public $proveedor_id;
    public $cantidad;
    public $fecha;
    public $observaciones;
    public $existencias = [];
    public $configExistencias = [];
    public $accion = 'create';
    public $existenciaSeleccionada = null;
    public $modalDetalle = false;
    public $modalPagos = false;
    public $reposicionParaPago = null;
    public $pagos = [];
    public $modalError = false;
    public $mensajeError = '';
    public $confirmingDeleteReposicionId = null;
    protected $rules = [
        'existencia_id' => 'required|exists:existencias,id',
        'personal_id' => 'required|exists:personals,id',
        'proveedor_id' => 'nullable|exists:proveedors,id',
        'cantidad' => 'required|integer|min:1',
        'fecha' => 'required|date',
        'observaciones' => 'nullable|string|max:500',
    ];

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'reposicion_id',
            'existencia_id',
            'personal_id',
            'proveedor_id',
            'cantidad',
            'fecha',
            'observaciones',
            'codigo'
        ]);

        $this->accion = $accion;

        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        if (!$personal) {
            $this->mensajeError = "No estás asignado a ningún personal válido.";
            $this->modalError = true;
            return;
        }

        $this->personal_id = $personal->id;

        $queryExistencias = Existencia::with('existenciable', 'sucursal')
            ->orderBy('id');

        if ($rol === 2) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $queryExistencias->where('sucursal_id', $sucursal_id);
        }

        $this->existencias = $queryExistencias->get();

        if ($accion === 'create') {
            $this->fecha = now()->format('Y-m-d\TH:i');
            $this->codigo = 'R-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        }

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }
    public function editar($id)
    {
        $reposicion = Reposicion::findOrFail($id);

        $this->reposicion_id = $reposicion->id;
        $this->existencia_id = $reposicion->existencia_id;
        $this->personal_id = $reposicion->personal_id;
        $this->proveedor_id = $reposicion->proveedor_id;
        $this->cantidad = $reposicion->cantidad;
        $this->fecha = $reposicion->fecha;
        $this->observaciones = $reposicion->observaciones;
        $this->codigo = $reposicion->codigo;
        $this->estado_revision = $reposicion->estado_revision;
    }

    public function guardar()
    {
        $this->validate();

        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $this->personal_id = $usuario->personal->id;

        $cantidadAnterior = $this->reposicion_id
            ? Reposicion::find($this->reposicion_id)->cantidad
            : 0;

        $data = [
            'codigo' => $this->codigo,
            'existencia_id' => $this->existencia_id,
            'personal_id' => $this->personal_id,
            'proveedor_id' => $this->proveedor_id,
            'cantidad' => $this->cantidad,
            'fecha'        => \Carbon\Carbon::parse($this->fecha)->format('Y-m-d H:i:s'),
            'observaciones' => $this->observaciones,
            'estado_revision' => $this->estado_revision ?? 0,
        ];
        if (!$this->reposicion_id) {
            $data['cantidad_inicial'] = $this->cantidad;
        }

        $reposicion = Reposicion::updateOrCreate(
            ['id' => $this->reposicion_id],
            $data
        );

        $existencia = Existencia::find($this->existencia_id);
        $existencia->cantidad += ($this->cantidad - $cantidadAnterior);
        $existencia->save();

        $this->cerrarModal();
        session()->flash('message', 'Reposición guardada correctamente!');
    }


    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'reposicion_id',
            'existencia_id',
            'personal_id',
            'proveedor_id',
            'cantidad',
            'fecha',
            'observaciones'
        ]);
        $this->resetErrorBag();
    }
    public function verDetalleReposicion($id)
    {
        $this->reposicionSeleccionada = Reposicion::with([
            'existencia.existenciable',
            'personal',
            'proveedor',
            'comprobantes'
        ])->findOrFail($id);

        $this->existenciaSeleccionada = $this->reposicionSeleccionada->existencia;

        $this->modalDetalle = true;
    }
    public function cerrarModalDetalleReposicion()
    {
        $this->reposicionSeleccionada = null;
        $this->modalDetalle = false;
    }
    public function abrirModalConfigGlobal()
    {
        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        $queryExistencias = Existencia::with('existenciable')
            ->where('existenciable_type', '!=', \App\Models\Producto::class)
            ->whereNull('sucursal_id')
            ->orderBy('id');

        if ($rol === 2 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
        }
        $existencias = $queryExistencias->get();
        $this->existencias = $existencias;
        $this->configExistencias = $existencias
            ->sortByDesc('updated_at')
            ->mapWithKeys(fn($ex) => [
                $ex->id => [
                    'sucursal_id' => $ex->sucursal_id,
                ],
            ])->toArray();

        $this->modalConfigGlobal = true;
    }

    public function guardarConfigGlobal()
    {
        foreach ($this->configExistencias as $id => $config) {
            $existencia = Existencia::find($id);
            if ($existencia) {
                $existencia->update([
                    'sucursal_id' => $config['sucursal_id'] ?: null,
                ]);
            }
        }

        $this->modalConfigGlobal = false;
    }

    public function abrirModalPagos($reposicion_id)
    {
        $this->reposicionParaPago = $reposicion_id;
        $this->pagos = \App\Models\ComprobantePago::where('reposicion_id', $reposicion_id)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'codigo' => $p->codigo ?? 'PAGO-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'fecha' => $p->fecha ? Carbon::parse($p->fecha)->format('Y-m-d') : now()->format('Y-m-d'),
                'monto' => $p->monto,
                'observaciones' => $p->observaciones,
                'imagen' => $p->imagen,
            ])->toArray();

        $this->modalPagos = true;
    }

    public function agregarPago()
    {
        $this->pagos[] = [
            'id' => null,
            'codigo' => 'PAGO-' . now()->format('Ymd') . '-' . str_pad(count($this->pagos) + 1, 3, '0', STR_PAD_LEFT),
            'fecha' => now()->format('Y-m-d'),
            'monto' => null,
            'observaciones' => null,
            'imagen' => null,
        ];
    }

    public function eliminarPago($index)
    {
        $pago = $this->pagos[$index] ?? null;
        if ($pago && isset($pago['id']) && $pago['id']) {
            \App\Models\ComprobantePago::find($pago['id'])?->delete();
        }
        unset($this->pagos[$index]);
        $this->pagos = array_values($this->pagos);
    }

    public function guardarPagos()
    {
        foreach ($this->pagos as $index => $pago) {
            $imagenPath = $pago['imagen'];
            if ($imagenPath instanceof \Illuminate\Http\UploadedFile) {
                $imagenPath = $pago['imagen']->store('pagos', 'public');
            }
            \App\Models\ComprobantePago::updateOrCreate(
                ['id' => $pago['id'] ?? 0],
                [
                    'reposicion_id' => $this->reposicionParaPago,
                    'codigo' => $pago['codigo'],
                    'monto' => $pago['monto'],
                    'fecha' => $pago['fecha'] ?? now()->format('Y-m-d'),
                    'observaciones' => $pago['observaciones'] ?? null,
                    'imagen' => $imagenPath,
                ]
            );
        }
        $this->reset(['pagos']);
        $this->modalPagos = false;
    }

    public function render()
    {
        $usuario  = auth()->user();
        $rol      = $usuario->rol_id;
        $personal = $usuario->personal;
        $queryExistencias = Existencia::with('existenciable')
            ->where('cantidad', '>', 0)
            
            ->orderBy('id');

        if ($rol === 2 && $personal) {
            $sucursal_id = $personal->trabajos()
                ->latest('fechaInicio')
                ->value('sucursal_id');

            $queryExistencias->where('sucursal_id', $sucursal_id);
        }

        $existencias = $queryExistencias->get();

        // REPOSICIONES
        $queryReposiciones = Reposicion::with(['existencia.existenciable', 'personal', 'proveedor'])
            ->when($this->searchCodigo, fn($q) => $q->where('codigo', 'like', '%' . $this->searchCodigo . '%'));

        if ($rol === 2 && $personal) {
            $sucursal_id = $personal->trabajos()
                ->latest('fechaInicio')
                ->value('sucursal_id');

            // Solo filtrar por sucursal de la existencia, NO por personal_id
            $queryReposiciones->whereHas('existencia', fn($q) => $q->where('sucursal_id', $sucursal_id));
        }

        $reposiciones = $queryReposiciones->orderBy('fecha', 'desc')->get();

        $personalList = Personal::whereHas('trabajos', fn($q) => $q->where('estado', 1))
            ->with(['trabajos' => fn($q) => $q->where('estado', 1)
                ->latest('fechaInicio')
                ->with('sucursal')])
            ->get();

        return view('livewire.stocks', [
            'existencias'  => $existencias,
            'reposiciones' => $reposiciones,
            'personal'     => $personalList,
            'proveedores'  => Proveedor::where('estado', 1)->get(),
            'sucursales'   => Sucursal::all(),
        ]);
    }


    public function eliminarReposicion($id)
    {
        $reposicion = Reposicion::with('existencia', 'asignados')->find($id);

        if (!$reposicion) {
            $this->mensajeError = "La reposición no existe.";
            $this->modalError = true;
            return;
        }
        $existencia = $reposicion->existencia;
        DB::transaction(function () use ($reposicion, $existencia) {
            $loteId = $reposicion->id;
            foreach ($reposicion->asignados as $asignado) {
                $cantidadDelLote = $asignado->reposiciones()
                    ->where('reposicion_id', $loteId)
                    ->first()?->pivot->cantidad ?? 0;
                if ($cantidadDelLote > 0) {
                    $asignado->cantidad -= $cantidadDelLote;
                    $asignado->reposiciones()->detach($loteId);
                    if ($asignado->cantidad <= 0) {
                        $asignado->delete();
                    } else {
                        $asignado->save();
                    }
                }
            }
            if ($existencia) {
                $existencia->cantidad -= $reposicion->cantidad;
                if ($existencia->cantidad < 0) $existencia->cantidad = 0;
                $existencia->save();
            }
            $reposicion->delete();
        });

        session()->flash('message', 'Reposición eliminada correctamente y asignaciones actualizadas.');
    }
    public function confirmarEliminarReposicion($id)
    {
        $this->confirmingDeleteReposicionId = $id;
    }

    public function eliminarReposicionConfirmado()
    {
        if (!$this->confirmingDeleteReposicionId) return;

        $this->eliminarReposicion($this->confirmingDeleteReposicionId);

        $this->confirmingDeleteReposicionId = null;
    }
    public function toggleEstado($id)
    {
        $reposicion = Reposicion::find($id);
        if ($reposicion) {
            $reposicion->estado_revision = !$reposicion->estado_revision;
            $reposicion->save();
            session()->flash('message', 'Estado de reposición actualizado correctamente.');
        } else {
            $this->mensajeError = "La reposición no existe.";
            $this->modalError = true;
        }
    }
}
