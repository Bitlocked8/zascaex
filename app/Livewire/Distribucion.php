<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Distribucion as DistribucionModel;
use App\Models\Pedido;
use App\Models\Coche;
use App\Models\Personal;

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
    public $estado = 1;

    public $pedidos_seleccionados = [];
    public $pedidosDeDistribucion = [];

    public $coches = [];
    public $personals = [];
    public $pedidos = []; // ⚠ Inicializamos como array

    public $search = '';

    public function mount($distribucion_id = null)
    {
        $this->coches = Coche::where('estado', 1)->get();
        $this->personals = Personal::where('estado', 1)->get();
        $this->loadPedidosDisponibles($distribucion_id);

        $this->distribucionModel = $distribucion_id
            ? DistribucionModel::with('pedidos')->find($distribucion_id)
            : new DistribucionModel();

        $this->loadDistribucionData();
    }

    private function loadPedidosDisponibles($distribucion_id = null)
    {
        $query = Pedido::where('estado_pedido', 0)
            ->whereDoesntHave('distribuciones', function ($subquery) use ($distribucion_id) {
                $subquery->where('distribucions.estado', 1);
                if ($distribucion_id) {
                    $subquery->where('distribucions.id', '!=', $distribucion_id);
                }
            });

        if ($distribucion_id) {
            $query = $query->orWhereHas('distribuciones', function ($q) use ($distribucion_id) {
                $q->where('distribucions.id', $distribucion_id);
            });
        }

        $this->pedidos = $query->get();
    }

    private function loadDistribucionData()
    {
        if ($this->distribucionModel && $this->distribucionModel->exists) {
            $this->codigo = $this->distribucionModel->codigo;
            $this->fecha_asignacion = $this->distribucionModel->fecha_asignacion;
            $this->fecha_entrega = $this->distribucionModel->fecha_entrega;
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
                'personal_id',
                'observaciones',
                'pedidos_seleccionados',
                'estado'
            ]);
            $this->fecha_asignacion = now()->toDateString();
        }
    }

    public function abrirModal($distribucion_id = null)
    {
        if ($distribucion_id) {
            $this->distribucionModel = DistribucionModel::with('pedidos')->find($distribucion_id);
            $this->loadPedidosDisponibles($distribucion_id);
        } else {
            $this->distribucionModel = new DistribucionModel();
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
            'personal_id',
            'observaciones',
            'pedidos_seleccionados',
            'estado',
        ]);

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
            'personal_id' => 'required',
            'pedidos_seleccionados' => 'nullable|array',
        ]);

        $dist = $this->distribucionModel;

        if (!$dist->exists || !$dist->codigo) {
            $dist->codigo = 'DIS-' . mt_rand(1000000000, 9999999999);
        }

        if (!$dist->fecha_asignacion) {
            $dist->fecha_asignacion = now()->toDateString();
        }

        $dist->fecha_entrega = $this->fecha_entrega;
        $dist->coche_id = $this->coche_id;
        $dist->personal_id = $this->personal_id;
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
        $distribucion = DistribucionModel::with('pedidos')->find($id);
        $this->pedidosDeDistribucion = $distribucion ? $distribucion->pedidos : [];
        $this->modalPedidos = true;
    }

    public function cerrarModalPedidos()
    {
        $this->modalPedidos = false;
        $this->pedidosDeDistribucion = [];
    }

    public function getPedidosAsignadosProperty()
    {
        return Pedido::whereIn('id', $this->pedidos_seleccionados)->get();
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
            'pedidosAsignados' => $this->pedidosAsignados, // 🔹 PASARLA AL VIEW
        ]);
    }
}
