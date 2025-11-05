<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sucursal;
use App\Models\Pedido;
use Carbon\Carbon;

class Reportecompra extends Component
{
    public $sucursal_id = '';
    public $ordenCantidad = 'desc';
    public $fechaInicio;
    public $fechaFin;
    public $inicioDia;
    public $inicioMes;
    public $inicioAnio;
    public $finDia;
    public $finMes;
    public $finAnio;
    public $totalCantidad = 0;
    public $totalMonto = 0;

    protected $listeners = ['actualizarDatos' => '$refresh'];

  public function mount()
{
    $this->inicioDia = null;
    $this->inicioMes = null;
    $this->inicioAnio = null;
    $this->finDia = null;
    $this->finMes = null;
    $this->finAnio = null;
}

    public function updated($property)
    {
        if (in_array($property, [
            'sucursal_id', 'ordenCantidad',
            'inicioDia', 'inicioMes', 'inicioAnio',
            'finDia', 'finMes', 'finAnio'
        ])) {
            $this->dispatch('actualizarDatos');
        }
    }

    public function render()
    {
        $productosPedidos = collect();
        $this->totalCantidad = 0;
        $this->totalMonto = 0;

        $pedidos = Pedido::with('detalles.existencia.sucursal', 'detalles.existencia.existenciable')
            ->when(
                $this->inicioDia && $this->inicioMes && $this->inicioAnio &&
                $this->finDia && $this->finMes && $this->finAnio,
                function ($query) {
                    try {
                        $inicio = Carbon::create($this->inicioAnio, $this->inicioMes, $this->inicioDia)->startOfDay();
                        $fin = Carbon::create($this->finAnio, $this->finMes, $this->finDia)->endOfDay();
                        $query->whereBetween('fecha_pedido', [$inicio, $fin]);
                    } catch (\Exception $e) {
                        logger()->error('Error al crear fechas en Reportecompra', [
                            'inicio' => [$this->inicioDia, $this->inicioMes, $this->inicioAnio],
                            'fin' => [$this->finDia, $this->finMes, $this->finAnio],
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            )
            ->get();

        foreach ($pedidos as $pedido) {
            foreach ($pedido->detalles as $detalle) {
                $existencia = $detalle->existencia;
                $producto = $existencia?->existenciable;
                $sucursal = $existencia?->sucursal;

                if (!$existencia || !$producto || !$sucursal) continue;
                if ($this->sucursal_id && $sucursal->id != $this->sucursal_id) continue;

                $descripcion = $producto->descripcion ?? 'Sin descripción';
                $precio = $producto->precioReferencia ?? 0;
                $cantidad = $detalle->cantidad ?? 0;
                $subtotal = $precio * $cantidad;

                $clave = $sucursal->id . '-' . $descripcion;

                if ($productosPedidos->has($clave)) {
                    $productosPedidos[$clave]['cantidad'] += $cantidad;
                    $productosPedidos[$clave]['subtotal'] += $subtotal;
                } else {
                    $productosPedidos[$clave] = [
                        'sucursal' => $sucursal->nombre,
                        'producto' => $descripcion,
                        'cantidad' => $cantidad,
                        'precio' => $precio,
                        'subtotal' => $subtotal,
                    ];
                }

                $this->totalCantidad += $cantidad;
                $this->totalMonto += $subtotal;
            }
        }

        $productosPedidos = $productosPedidos
            ->sortBy('cantidad', SORT_REGULAR, $this->ordenCantidad === 'desc')
            ->values();

        return view('livewire.reportecompra', [
            'productosPedidos' => $productosPedidos,
            'totalCantidad' => $this->totalCantidad,
            'totalMonto' => $this->totalMonto,
            'sucursales' => Sucursal::orderBy('nombre')->get(),
        ]);
    }
}
