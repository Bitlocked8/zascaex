<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Distribucion as DistribucionModel;
use App\Models\Pedido;
use App\Models\Coche;
use App\Models\Personal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Distribucion extends Component
{
    public $modalDistribucion = false;
    public $modalPedidos = false;
    public $distribucionModel;

    public $codigo;
    public $fecha_asignacion;
    public $fecha_entrega;
    public $coche_id;
    public $personal_id;
    public $observaciones;
    public $estado = 0;

    public $pedidos_seleccionados = [];
    public $pedidosDeDistribucion = [];

    public $coches = [];
    public $personals = [];
    public $pedidos = [];
    public $search = '';

    public $confirmingDeleteId = null;

    public function mount($distribucion_id = null)
    {
        $this->coches = Coche::where('estado', 1)->get();
        $this->personals = Personal::where('estado', 1)->get();
        $this->loadPedidosDisponibles($distribucion_id);

        $this->distribucionModel = $distribucion_id
            ? DistribucionModel::with('pedidos')->find($distribucion_id)
            : new DistribucionModel();

        if (!$this->distribucionModel->exists) {
            $this->personal_id = optional(auth()->user()->personal)->id;
        }

        $this->loadDistribucionData();
    }

    private function loadPedidosDisponibles($distribucion_id = null)
    {
        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $sucursalId = optional($usuario->personal->trabajos()->latest()->first())->sucursal_id;

        $this->pedidos = Pedido::with([
            'solicitudPedido.cliente',
            'detalles.existencia.sucursal',
            'detalles.existencia.existenciable',
        ])
        ->whereDoesntHave('distribuciones', function ($subquery) use ($distribucion_id) {
            if ($distribucion_id) {
                $subquery->where('distribucions.id', '!=', $distribucion_id);
            }
        })
        ->when($rol === 3 && $sucursalId, function ($query) use ($sucursalId) {
            $query->whereHas('detalles.existencia', fn($q) => $q->where('sucursal_id', $sucursalId));
        })
        ->get();
    }

    private function loadDistribucionData()
    {
        if ($this->distribucionModel && $this->distribucionModel->exists) {
            $this->codigo = $this->distribucionModel->codigo;
            $this->fecha_asignacion = $this->distribucionModel->fecha_asignacion
                ? Carbon::parse($this->distribucionModel->fecha_asignacion)->format('Y-m-d\TH:i:s')
                : now()->format('Y-m-d\TH:i:s');
            $this->fecha_entrega = $this->distribucionModel->fecha_entrega
                ? Carbon::parse($this->distribucionModel->fecha_entrega)->format('Y-m-d\TH:i:s')
                : null;
            $this->coche_id = $this->distribucionModel->coche_id;
            $this->personal_id = $this->distribucionModel->personal_id;
            $this->observaciones = $this->distribucionModel->observaciones;
            $this->estado = $this->distribucionModel->estado;
            $this->pedidos_seleccionados = $this->distribucionModel->pedidos->pluck('id')->toArray();
        } else {
            $this->reset([
                'codigo',
                'fecha_entrega',
                'coche_id',
                'observaciones',
                'pedidos_seleccionados',
                'estado'
            ]);
            $this->fecha_asignacion = now()->format('Y-m-d\TH:i:s');
        }
    }

    public function abrirModal($distribucion_id = null)
    {
        if ($distribucion_id) {
            $this->distribucionModel = DistribucionModel::with('pedidos')->find($distribucion_id);
            $this->loadPedidosDisponibles($distribucion_id);
        } else {
            $this->distribucionModel = new DistribucionModel();
            $this->personal_id = optional(auth()->user()->personal)->id;
            $this->loadPedidosDisponibles();
        }

        $this->loadDistribucionData();
        $this->modalDistribucion = true;
    }

    public function cerrarModal()
    {
        $this->reset([
            'modalDistribucion',
            'distribucionModel',
            'codigo',
            'fecha_asignacion',
            'fecha_entrega',
            'coche_id',
            'observaciones',
            'pedidos_seleccionados',
            'estado',
        ]);
        $this->personal_id = optional(auth()->user()->personal)->id;
        $this->loadPedidosDisponibles();
    }

    public function agregarPedido($pedido_id)
    {
        if (!in_array($pedido_id, $this->pedidos_seleccionados)) {
            $this->pedidos_seleccionados[] = $pedido_id;
        }
    }

    public function quitarPedido($pedido_id)
    {
        $this->pedidos_seleccionados = array_values(
            array_filter($this->pedidos_seleccionados, fn($id) => $id != $pedido_id)
        );
        $this->loadPedidosDisponibles($this->distribucionModel->id ?? null);
    }

    public function guardarDistribucion()
    {
        $this->validate([
            'pedidos_seleccionados' => 'nullable|array',
        ]);

        if (!$this->distribucionModel instanceof DistribucionModel) {
            $this->distribucionModel = new DistribucionModel();
        }

        $dist = $this->distribucionModel;

        if (!$dist->exists || !$dist->codigo) {
            $dist->codigo = 'DIS-' . mt_rand(1000000000, 9999999999);
        }

        if (!$dist->exists) {
            $dist->fecha_asignacion = now();
            $dist->personal_id = auth()->user()->personal->id ?? null;
        }

        $rol = auth()->user()->rol_id;
        $sucursalId = optional(auth()->user()->personal->trabajos()->latest()->first())->sucursal_id;

        if ($rol === 3 && $sucursalId && !empty($this->pedidos_seleccionados)) {
            $this->pedidos_seleccionados = Pedido::whereIn('id', $this->pedidos_seleccionados)
                ->whereHas('detalles.existencia', fn($q) => $q->where('sucursal_id', $sucursalId))
                ->pluck('id')
                ->toArray();
        }

        if (empty($this->pedidos_seleccionados)) {
            session()->flash('message', 'No se puede guardar una distribución sin pedidos.');
            return;
        }

        $dist->fecha_entrega = $this->fecha_entrega
            ? Carbon::parse($this->fecha_entrega)
            : null;
        $dist->coche_id = $this->coche_id;
        $dist->observaciones = $this->observaciones;
        $dist->estado = $this->estado;

        $dist->save();
        $dist->pedidos()->sync($this->pedidos_seleccionados ?? []);

        session()->flash('message', 'Distribución guardada correctamente.');
        $this->cerrarModal();
    }

    public function editarDistribucion($id)
    {
        $this->abrirModal($id);
    }

    public function verPedidos($id)
    {
        $distribucion = DistribucionModel::with([
            'personal',
            'coche',
            'pedidos.solicitudPedido.cliente',
        ])->find($id);

        $this->distribucionModel = $distribucion;
        $this->pedidosDeDistribucion = $distribucion ? $distribucion->pedidos : collect();
        $this->modalPedidos = true;
    }

    public function cerrarModalPedidos()
    {
        $this->modalPedidos = false;
        $this->pedidosDeDistribucion = [];
    }

    public function confirmarEliminar($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function eliminarDistribucion()
    {
        if (!$this->confirmingDeleteId)
            return;

        DB::transaction(function () {
            $distribucion = DistribucionModel::with('pedidos')->findOrFail($this->confirmingDeleteId);
            $distribucion->pedidos()->detach();
            $distribucion->delete();
        });

        $this->confirmingDeleteId = null;
        session()->flash('message', 'Distribución eliminada correctamente.');
    }

    public function cancelarEliminar()
    {
        $this->confirmingDeleteId = null;
    }

    public function getPedidosAsignadosProperty()
    {
        return Pedido::whereIn('id', $this->pedidos_seleccionados)->get();
    }

    public function establecerFechaActual()
    {
        $this->fecha_entrega = Carbon::now()->format('m/d/y H:i:s');
    }

    public function render()
    {
        return view('livewire.distribucion', [
            'distribuciones' => DistribucionModel::with(['pedidos', 'coche', 'personal'])
                ->where(function ($q) {
                    $q->where('codigo', 'like', "%{$this->search}%")
                        ->orWhereHas('personal', fn($p) => $p->where('nombres', 'like', "%{$this->search}%"))
                        ->orWhereHas('coche', fn($c) => $c->where('placa', 'like', "%{$this->search}%"));
                })
                ->latest()
                ->get(),
            'pedidosAsignados' => $this->pedidosAsignados,
        ]);
    }
}
