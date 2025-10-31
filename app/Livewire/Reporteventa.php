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
        // Inicializamos las fechas con formato datetime-local
        $this->fechaInicio = Carbon::now()->startOfMonth()->format('Y-m-d\TH:i');
        $this->fechaFin = Carbon::now()->endOfMonth()->format('Y-m-d\TH:i');
    }

    public function render()
    {
        $query = Pedido::with([
            'cliente',
            'personal',
            'detalles.existencia.existenciable',
            'pagoPedidos'
        ])->orderBy('fecha_pedido', 'desc');

        // 🔹 Filtros dinámicos
        if (!empty($this->codigo)) {
            $query->where('codigo', 'like', "%{$this->codigo}%");
        }

        if (!empty($this->cliente_id)) {
            $query->where('cliente_id', $this->cliente_id);
        }

        if (!empty($this->personal_id)) {
            $query->where('personal_id', $this->personal_id);
        }

        if ($this->estado_pedido !== '') {
            $query->where('estado_pedido', (int)$this->estado_pedido);
        }

        if ($this->estado_pago !== '') {
            $query->whereHas('pagoPedidos', function ($q) {
                $q->where('estado', (int)$this->estado_pago);
            });
        }

        // 🔹 Filtro por rango de fecha y hora
        if (!empty($this->fechaInicio) && !empty($this->fechaFin)) {
            $inicio = Carbon::parse($this->fechaInicio);
            $fin = Carbon::parse($this->fechaFin);

            $query->whereBetween('fecha_pedido', [$inicio, $fin]);
        }

        // 🔹 Obtener pedidos
        $pedidos = $query->get();

        // 🔹 Calcular totales
        $this->totalCantidad = $pedidos->sum(function ($pedido) {
            return $pedido->detalles->sum('cantidad');
        });

        $this->totalMonto = $pedidos->sum(function ($pedido) {
            return $pedido->detalles->sum(function ($detalle) {
                $precio = $detalle->existencia->existenciable->precioReferencia ?? 0;
                return $detalle->cantidad * $precio;
            });
        });

        return view('livewire.reporteventa', [
            'pedidos' => $pedidos,
            'clientes' => Cliente::orderBy('nombre')->get(),
            'personales' => Personal::orderBy('nombres')->get(),
            'totalCantidad' => $this->totalCantidad,
            'totalMonto' => $this->totalMonto,
        ]);
    }
}
