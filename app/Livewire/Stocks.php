<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Existencia;
use App\Models\Reposicion;
use App\Models\Sucursal;
use App\Models\Proveedor;
use App\Models\Personal;

class Stocks extends Component
{
    use WithFileUploads;

    public $existencias;
    public $reposiciones;
    public $proveedores;
    public $personal;
    public $sucursales;
    public $selectedSucursal;
    public $modalDetalle = false;
    public $existenciaSeleccionada;
    public $modal = false;
    public $reposicion_id = null;
    public $existencia_id;
    public $personal_id;
    public $proveedor_id;
    public $cantidad;
    public $precio_unitario;
    public $imagen;
    public $fecha;
    public $observaciones;
    public $accion = 'create';
    public $sucursalSeleccionada;
    public $modalConfigGlobal = false;
    public $configExistencias = [];

    protected $rules = [
        'existencia_id' => 'required|exists:existencias,id',
        'personal_id' => 'required|exists:personals,id',
        'proveedor_id' => 'nullable|exists:proveedors,id',
        'cantidad' => 'required|integer|min:1',
        'precio_unitario' => 'nullable|numeric|min:0',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        'fecha' => 'required|date',
        'observaciones' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->sucursales = Sucursal::all();
        $this->proveedores = Proveedor::all();
        $this->personal = Personal::all();
        $this->selectedSucursal = $this->sucursales->first()->id ?? null;
        $this->sucursalSeleccionada = $this->sucursales->first()->id ?? null;
        $this->filtrarPorSucursal($this->sucursalSeleccionada);
        $this->cargarExistencias();
        $this->cargarReposiciones();
    }

    public function filtrarPorSucursal($sucursalId)
    {
        $this->sucursalSeleccionada = $sucursalId;

        $this->existencias = Existencia::with('existenciable')
            ->where('sucursal_id', $sucursalId)
            ->orderBy('id')
            ->get()
            ->map(fn($ex) => $ex->stock_real = $ex->cantidad ? $ex : $ex);

        $this->reposiciones = Reposicion::with(['existencia.existenciable', 'personal', 'proveedor'])
            ->whereHas('existencia', fn($q) => $q->where('sucursal_id', $sucursalId))
            ->orderBy('fecha', 'desc')
            ->get();
    }

    public function cargarExistencias()
    {
        $this->existencias = Existencia::with('existenciable')
            ->orderBy('id')
            ->get()
            ->map(fn($ex) => $ex->stock_real = $ex->cantidad ? $ex : $ex);
    }

    public function cargarReposiciones()
    {
        $this->reposiciones = Reposicion::with(['existencia.existenciable', 'personal', 'proveedor'])
            ->orderBy('fecha', 'desc')
            ->get();
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'reposicion_id',
            'existencia_id',
            'personal_id',
            'proveedor_id',
            'cantidad',
            'precio_unitario',
            'imagen',
            'fecha',
            'observaciones'
        ]);

        $this->accion = $accion;
        $this->fecha = now()->format('Y-m-d');

        if ($accion === 'edit' && $id) $this->editar($id);

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
        $this->precio_unitario = $reposicion->precio_unitario;
        $this->imagen = null;
        $this->fecha = $reposicion->fecha;
        $this->observaciones = $reposicion->observaciones;
    }

    public function guardar()
    {
        $rules = $this->rules;
        if (!is_object($this->imagen)) $rules['imagen'] = 'nullable';
        $this->validate($rules);

        if ($this->imagen && is_object($this->imagen)) {
            $imagenPath = $this->imagen->store('reposiciones', 'public');
        } else {
            $imagenPath = $this->reposicion_id
                ? Reposicion::find($this->reposicion_id)->imagen
                : null;
        }

        $cantidadAnterior = $this->reposicion_id
            ? Reposicion::find($this->reposicion_id)->cantidad
            : 0;

        $reposicion = Reposicion::updateOrCreate(
            ['id' => $this->reposicion_id],
            [
                'existencia_id' => $this->existencia_id,
                'personal_id' => $this->personal_id,
                'proveedor_id' => $this->proveedor_id,
                'cantidad' => $this->cantidad,
                'precio_unitario' => $this->precio_unitario,
                'imagen' => $imagenPath,
                'fecha' => $this->fecha,
                'observaciones' => $this->observaciones,
            ]
        );

        $existencia = Existencia::find($this->existencia_id);
        $existencia->cantidad += ($this->cantidad - $cantidadAnterior);
        $existencia->save();

        $this->cerrarModal();
        $this->cargarReposiciones();
        $this->cargarExistencias();
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
            'precio_unitario',
            'imagen',
            'fecha',
            'observaciones'
        ]);
        $this->resetErrorBag();
    }

    public function abrirModalConfigGlobal()
    {
        $this->configExistencias = $this->existencias->mapWithKeys(fn($ex) => [
            $ex->id => ['cantidad_minima' => $ex->cantidadMinima, 'sucursal_id' => $ex->sucursal_id]
        ])->toArray();

        $this->modalConfigGlobal = true;
    }

    public function guardarConfigGlobal()
    {
        foreach ($this->configExistencias as $id => $config) {
            $existencia = Existencia::find($id);
            if ($existencia) {
                $existencia->update([
                    'cantidadMinima' => $config['cantidad_minima'],
                    'sucursal_id' => $config['sucursal_id'],
                ]);
            }
        }
        $this->modalConfigGlobal = false;
        $this->cargarExistencias();
    }

    public function modaldetalle($id)
    {
        $this->existenciaSeleccionada = Existencia::with('existenciable', 'sucursal')->findOrFail($id);
        $this->modalDetalle = true;
    }
    public function render()
    {
        return view('livewire.stocks', [
            'existencias' => $this->existencias,
            'reposiciones' => $this->reposiciones,
            'personal' => $this->personal,
            'proveedores' => $this->proveedores,
            'sucursales' => $this->sucursales,
        ]);
    }
}
