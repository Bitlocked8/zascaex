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
    public $cliente_id = '';
    public $personal_id = '';
    public $codigo = '';
    public $estado_pedido = '';
    public $estado_pago = '';

    public $totalCantidad = 0;
    public $totalMonto = 0;

    public function mount()
    {
        $this->fechaInicio = Carbon::now()->startOfMonth()->toDateString();
        $this->fechaFin = Carbon::now()->endOfMonth()->toDateString();
    }

    public function render()
    {
        $query = Pedido::with([
            'cliente',
            'personal',
            'detalles.existencia.existenciable',
            'pagoPedidos' // CORRECTO
        ])->orderBy('fecha_pedido', 'desc');

        if ($this->codigo) {
            $query->where('codigo', 'like', "%{$this->codigo}%");
        }

        if ($this->cliente_id) {
            $query->where('cliente_id', $this->cliente_id);
        }

        if ($this->personal_id) {
            $query->where('personal_id', $this->personal_id);
        }

        if ($this->estado_pedido !== '') {
            $query->where('estado_pedido', $this->estado_pedido);
        }

        if ($this->estado_pago !== '') {
            $query->whereHas('pagoPedidos', function ($q) {
                $q->where('estado', $this->estado_pago);
            });
        }

        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('fecha_pedido', [$this->fechaInicio, $this->fechaFin]);
        }

        $pedidos = $query->get();

        $this->totalCantidad = $pedidos->sum(fn($pedido) =>
            $pedido->detalles->sum('cantidad')
        );

        $this->totalMonto = $pedidos->sum(fn($pedido) =>
            $pedido->detalles->sum(fn($detalle) =>
                $detalle->cantidad * ($detalle->existencia->existenciable->precioReferencia ?? 0)
            )
        );

        return view('livewire.reporteventa', [
            'pedidos' => $pedidos,
            'clientes' => Cliente::orderBy('nombre')->get(),
            'personales' => Personal::orderBy('nombres')->get(),
            'totalCantidad' => $this->totalCantidad,
            'totalMonto' => $this->totalMonto,
        ]);
    }
}
