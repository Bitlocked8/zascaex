<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Producto;
use App\Models\Reposicion;
use Illuminate\Support\Facades\Auth;

class Pedidos extends Component
{
    public $pedido;
    public $cliente_id;
    public $personal_id;
    public $productoSeleccionado;
    public $cantidadSeleccionada;
    public $detalles = [];
    public $mensaje = null;
    public $tipoMensaje = 'success';
    public $modalPedido = false;
    public $estado_pedido = 0;
    public $fecha_pedido;

    public function mount($pedido_id = null)
    {
        $this->pedido = $pedido_id ? Pedido::find($pedido_id) : new Pedido();
        $this->personal_id = $this->pedido->personal_id ?? Auth::id();
        $this->fecha_pedido = $this->pedido->fecha_pedido ?? now();
    }


    public function abrirModal()
    {
        $this->modalPedido = true;
    }

    public function editarPedido($pedido_id)
    {
        $this->pedido = Pedido::with('detalles.existencia.sucursal')->find($pedido_id);
        $this->cliente_id = $this->pedido->cliente_id;
        $this->personal_id = $this->pedido->personal_id;
        $this->estado_pedido = $this->pedido->estado_pedido;

        $this->detalles = $this->pedido->detalles->map(function ($detalle) {
            $producto = $detalle->existencia->existenciable ?? null;
            $sucursal = $detalle->existencia->sucursal ?? null;

            return [
                'id' => $detalle->id,
                'existencia_id' => $detalle->existencia_id,
                'reposicion_id' => $detalle->reposicion_id,
                'cantidad' => $detalle->cantidad,
                'nombre' => $producto->descripcion ?? 'Sin nombre',
                'sucursal_id' => $sucursal->id ?? null,
                'sucursal_nombre' => $sucursal->nombre ?? 'Sin sucursal',
            ];
        })->toArray();

        $this->modalPedido = true;
    }


    public function cerrarModal()
    {
        $this->modalPedido = false;
        $this->detalles = [];
        $this->productoSeleccionado = null;
        $this->cantidadSeleccionada = null;
        $this->cliente_id = null;
        $this->personal_id = null;
        $this->estado_pedido = 0;
        $this->pedido = new Pedido();
    }

    public function agregarProducto()
    {
        if (!$this->productoSeleccionado || !$this->cantidadSeleccionada) {
            $this->setMensaje('Debe seleccionar un producto y cantidad', 'error');
            return;
        }

        $producto = Producto::with('existencias.reposiciones')->find($this->productoSeleccionado);
        if (!$producto) {
            $this->setMensaje('Producto no existe', 'error');
            return;
        }
        $cantidadDisponible = 0;
        foreach ($producto->existencias as $existencia) {
            foreach ($existencia->reposiciones as $reposicion) {
                if ($reposicion->estado_revision == 1 && $reposicion->cantidad > 0) {
                    $cantidadDisponible += $reposicion->cantidad;
                }
            }
        }

        if ($this->cantidadSeleccionada > $cantidadDisponible) {
            $this->setMensaje('No hay suficiente stock para este producto', 'error');
            return;
        }

        $cantidadRestante = $this->cantidadSeleccionada;
        $detalleTemporal = [];

        foreach ($producto->existencias as $existencia) {
            $lotes = $existencia->reposiciones()
                ->where('cantidad', '>', 0)
                ->where('estado_revision', 1)
                ->orderBy('created_at')
                ->get();

            foreach ($lotes as $lote) {
                if ($cantidadRestante <= 0) break;

                $consumir = min($cantidadRestante, $lote->cantidad);
                $lote->cantidad -= $consumir;
                $lote->save();

                $detalleTemporal[] = [
                    'existencia_id' => $existencia->id,
                    'reposicion_id' => $lote->id,
                    'cantidad' => $consumir,
                    'nombre' => $producto->descripcion ?? 'Sin nombre',
                ];

                $cantidadRestante -= $consumir;
            }
            if ($cantidadRestante <= 0) break;
        }


        if ($cantidadRestante > 0) {
            $this->setMensaje('No hay suficiente stock para este producto', 'error');
            return;
        }

        $this->detalles = array_merge($this->detalles, $detalleTemporal);
        $this->setMensaje('Producto agregado correctamente', 'success');
        $this->productoSeleccionado = null;
        $this->cantidadSeleccionada = null;
    }

    public function eliminarDetalle($index)
    {
        $detalle = $this->detalles[$index];

        $lote = Reposicion::find($detalle['reposicion_id']);
        if ($lote) {
            $lote->cantidad += $detalle['cantidad'];
            $lote->save();
        }

        if (isset($detalle['id'])) {
            $this->detalles[$index]['eliminar'] = true;
        } else {
            unset($this->detalles[$index]);
        }

        $this->detalles = array_values($this->detalles);
        $this->setMensaje('Detalle eliminado correctamente', 'success');
    }

    public function guardarPedido()
    {
        $this->validate([
            'cliente_id' => 'required',
            'personal_id' => 'required',
        ]);

        $pedido = $this->pedido;
        $pedido->cliente_id = $this->cliente_id;
        $pedido->personal_id = $this->personal_id ?? Auth::id();
        $pedido->estado_pedido = $this->estado_pedido;
        $pedido->fecha_pedido = $pedido->fecha_pedido ?? $this->fecha_pedido ?? now();

        if (!$pedido->exists) {
            $pedido->codigo = 'R-' . now()->format('YmdHis');
            $pedido->estado_pedido = 0;
        }

        $pedido->save();

        foreach ($this->detalles as $detalle) {
            if (isset($detalle['id']) && isset($detalle['eliminar']) && $detalle['eliminar']) {
                PedidoDetalle::find($detalle['id'])->delete();
            }
        }

        foreach ($this->detalles as $detalle) {
            if (!isset($detalle['id'])) {
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'existencia_id' => $detalle['existencia_id'],
                    'reposicion_id' => $detalle['reposicion_id'],
                    'cantidad' => $detalle['cantidad'],
                ]);
            }
        }

        $this->setMensaje('Pedido guardado correctamente', 'success');
        $this->cerrarModal();
    }


    private function setMensaje($texto, $tipo = 'success')
    {
        $this->mensaje = $texto;
        $this->tipoMensaje = $tipo;
    }

    public function render()
    {
        $productos = Producto::whereHas('existencias.reposiciones', function ($query) {
            $query->where('estado_revision', 1)
                ->where('cantidad', '>', 0);
        })->with(['existencias.reposiciones' => function ($query) {
            $query->where('estado_revision', 1)
                ->where('cantidad', '>', 0);
        }])->get();

        return view('livewire.pedidos', [
            'pedidos' => Pedido::with(['cliente', 'personal', 'detalles'])->latest()->get(),
            'productos' => $productos,
            'detalles' => $this->detalles,
        ]);
    }
}
