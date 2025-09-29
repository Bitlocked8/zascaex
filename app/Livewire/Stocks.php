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

class Stocks extends Component
{
    use WithFileUploads;
    public $searchCodigo = '';
    public $ultimaReposicion = null;
    public $modal = false;
    public $modalDetalle = false;
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

    public $modalPagos = false;
    public $reposicionParaPago = null; // ID de la reposición seleccionada
    public $pagos = [];

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
        $this->fecha = now()->format('Y-m-d');

        $this->existencias = Existencia::with('existenciable', 'sucursal')
            ->where('existenciable_type', '!=', \App\Models\Producto::class)
            ->orderBy('id')
            ->get();

        if ($accion === 'create') {
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
    }

    public function guardar()
    {
        $cantidadAnterior = $this->reposicion_id
            ? Reposicion::find($this->reposicion_id)->cantidad
            : 0;

        $reposicion = Reposicion::updateOrCreate(
            ['id' => $this->reposicion_id],
            [
                'codigo' => $this->codigo,
                'existencia_id' => $this->existencia_id,
                'personal_id' => $this->personal_id,
                'proveedor_id' => $this->proveedor_id,
                'cantidad' => $this->cantidad,
                'fecha' => $this->fecha,
                'observaciones' => $this->observaciones,
            ]
        );

        $existencia = Existencia::find($this->existencia_id);
        $existencia->cantidad += ($this->cantidad - $cantidadAnterior);
        $existencia->save();

        $this->cerrarModal();
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

    // Modal detalle
    public function modaldetalle($id)
    {
        $this->existenciaSeleccionada = Existencia::with(['existenciable', 'sucursal', 'reposiciones'])->findOrFail($id);
        $this->ultimaReposicion = $this->existenciaSeleccionada->reposiciones->first();
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->existenciaSeleccionada = null;
        $this->ultimaReposicion = null;
    }

    public function abrirModalConfigGlobal()
    {
        $existencias = Existencia::with('existenciable')
            ->where('existenciable_type', '!=', \App\Models\Producto::class)
            ->orderBy('id')
            ->get();
        $this->existencias = $existencias;
        $this->configExistencias = $existencias
            ->sortByDesc('updated_at')
            ->mapWithKeys(fn($ex) => [
                $ex->id => [
                    'cantidad_minima' => $ex->cantidadMinima,
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
                    'cantidadMinima' => $config['cantidad_minima'] ?? 0,
                    'sucursal_id' => $config['sucursal_id'] ?: null,
                ]);
            }
        }
        $this->existencias = Existencia::with('existenciable')
            ->where('existenciable_type', '!=', \App\Models\Producto::class)
            ->orderBy('id')
            ->get();

        $this->modalConfigGlobal = false;
    }

    public function abrirModalPagos($reposicion_id)
    {
        $this->reposicionParaPago = $reposicion_id;
        $this->pagos = \App\Models\ComprobantePago::where('reposicion_id', $reposicion_id)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'codigo' => $p->codigo ?? 'PAGO-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                    'fecha' => $p->fecha ? \Carbon\Carbon::parse($p->fecha)->format('Y-m-d') : now()->format('Y-m-d'),

                    'monto' => $p->monto,
                    'observaciones' => $p->observaciones,
                    'imagen' => $p->imagen,
                ];
            })
            ->toArray();
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

            // Si es un archivo subido (instancia de UploadedFile)
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
        return view('livewire.stocks', [
            'existencias' => Existencia::with('existenciable')
                ->where('existenciable_type', '!=', \App\Models\Producto::class)
                ->orderBy('id')
                ->get(),
            'reposiciones' => Reposicion::with(['existencia.existenciable', 'personal', 'proveedor'])
                ->when($this->searchCodigo, function ($query) {
                    $query->where('codigo', 'like', '%' . $this->searchCodigo . '%');
                })
                ->where(function ($q) {
                    $q->whereHas('proveedor', fn($q2) => $q2->where('estado', 1))
                        ->orWhereNull('proveedor_id'); // reposiciones sin proveedor
                })
                ->orderBy('fecha', 'desc')
                ->get(),
            'personal' => Personal::whereHas('trabajos', fn($q) => $q->where('estado', 1))
                ->with(['trabajos' => fn($q) => $q->where('estado', 1)->latest('fechaInicio')->with('sucursal')])
                ->get(),
            'proveedores' => Proveedor::where('estado', 1)->get(),
            'sucursales' => Sucursal::all(),
        ]);
    }
}
