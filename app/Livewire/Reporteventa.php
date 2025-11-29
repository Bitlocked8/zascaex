<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Personal;
use Carbon\Carbon;

class Reporteventa extends Component
{
    public $fechaInicio;
    public $fechaFin;
    public $cliente_id = null;
    public $personal_id = null;
    public $codigo = '';
    public $estado_pedido = null;
    public $producto = '';
    public $totalCantidad = 0;
    public $totalMonto = 0;

    public function mount()
    {
        $this->fechaInicio = now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
{
    $query = Pedido::with([
        'solicitudPedido.cliente',
        'personal',
        'detalles.existencia.existenciable',
        'pagoPedidos',
    ])->orderBy('fecha_pedido', 'desc');

    // Filtros existentes
    if ($this->codigo !== '') {
        $query->where('codigo', 'like', "%{$this->codigo}%");
    }

    if ($this->cliente_id) {
        $query->whereHas('solicitudPedido.cliente', fn($q) => $q->where('id', $this->cliente_id));
    }

    if ($this->personal_id) {
        $query->where('personal_id', $this->personal_id);
    }

    if ($this->estado_pedido !== null) {
        $query->where('estado_pedido', (int) $this->estado_pedido);
    }

    if ($this->fechaInicio && $this->fechaFin) {
        $inicio = Carbon::parse($this->fechaInicio)->startOfDay();
        $fin = Carbon::parse($this->fechaFin)->endOfDay();
        $query->whereBetween('fecha_pedido', [$inicio, $fin]);
    }

  if ($this->producto !== '') {
    $query->whereHas('detalles.existencia.existenciable', function($q) {
        $q->where('descripcion', 'like', "%{$this->producto}%");
    });
}


    $pedidos = $query->get();

    $this->totalCantidad = $pedidos->sum(fn($pedido) => $pedido->detalles->sum('cantidad'));
    $this->totalMonto = $pedidos->sum(fn($pedido) => $pedido->detalles->sum(fn($detalle) => ($detalle->existencia->existenciable->precioReferencia ?? 0) * $detalle->cantidad));

    return view('livewire.reporteventa', [
        'pedidos' => $pedidos,
        'clientes' => Cliente::orderBy('nombre')->get(),
        'personales' => Personal::orderBy('nombres')->get(),
    ]);
}
}
