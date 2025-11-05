<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\PagoPedido;
use Livewire\Component;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Producto;
use App\Models\Otro;
use App\Models\Reposicion;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class Pedidos extends Component
{
    use WithFileUploads;
    public $sucursal_id = null;

    public $modalDetallePedido = false;
    public $pedidoDetalle;
    public $clientes;
    public $searchCliente = '';
    public $imagenPreviewModal = null;
    public $pedido;
    public $cliente_id;
    public $personal_id;
    public $productoSeleccionado;
    public $otroSeleccionado;
    public $tipoProducto = 'producto';
    public $cantidadSeleccionada;
    public $detalles = [];
    public $mensaje = null;
    public $tipoMensaje = 'success';
    public $modalPedido = false;
    public $estado_pedido = 0;
    public $fecha_pedido;

    public $pedidoParaPago;
    public $pagos = [];
    public $modalPagos = false;

    public function mount($pedido_id = null)
    {
        $this->pedido = $pedido_id ? Pedido::find($pedido_id) : new Pedido();
        $this->personal_id = $this->pedido->personal_id ?? Auth::id();
        $this->fecha_pedido = $this->pedido->fecha_pedido ? Carbon::parse($this->pedido->fecha_pedido) : now();

        $this->clientes = Cliente::orderBy('nombre')->get();
    }

    public function abrirModal()
    {
        $this->modalPedido = true;
    }

    public function editarPedido($pedido_id)
    {
        $this->pedido = Pedido::with(['detalles.existencia.existenciable', 'detalles.existencia.sucursal'])->find($pedido_id);
        $this->cliente_id = $this->pedido->cliente_id;
        $this->personal_id = $this->pedido->personal_id;
        $this->estado_pedido = $this->pedido->estado_pedido;
        $this->fecha_pedido = $this->pedido->fecha_pedido ? Carbon::parse($this->pedido->fecha_pedido) : now();

        $this->detalles = $this->pedido->detalles->map(function ($detalle) {
            $existenciable = $detalle->existencia->existenciable ?? null;
            $sucursal = $detalle->existencia->sucursal ?? null;
            $tipo = $existenciable instanceof Producto ? 'producto' : 'otro';

            return [
                'id' => $detalle->id,
                'existencia_id' => $detalle->existencia_id,
                'reposicion_id' => $detalle->reposicion_id,
                'cantidad' => $detalle->cantidad,
                'nombre' => $existenciable->descripcion ?? 'Sin nombre',
                'tipo' => $tipo,
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
        if (!$this->cantidadSeleccionada) {
            $this->setMensaje('Debe seleccionar una cantidad', 'error');
            return;
        }
        if ($this->tipoProducto === 'producto' && !$this->productoSeleccionado) {
            $this->setMensaje('Debe seleccionar un producto', 'error');
            return;
        }

        if ($this->tipoProducto === 'otro' && !$this->otroSeleccionado) {
            $this->setMensaje('Debe seleccionar un item', 'error');
            return;
        }

        $modelo = null;
        $descripcion = '';

        if ($this->tipoProducto === 'producto') {
            $modelo = Producto::with('existencias.reposiciones')->find($this->productoSeleccionado);
            $descripcion = $modelo->descripcion ?? 'Sin nombre';
        } else {
            $modelo = Otro::with('existencias.reposiciones')->find($this->otroSeleccionado);
            $descripcion = $modelo->descripcion ?? 'Sin nombre';
        }

        if (!$modelo) {
            $this->setMensaje('Item no existe', 'error');
            return;
        }

        $cantidadDisponible = 0;
        foreach ($modelo->existencias as $existencia) {
            foreach ($existencia->reposiciones as $reposicion) {
                if ($reposicion->estado_revision == 1 && $reposicion->cantidad > 0) {
                    $cantidadDisponible += $reposicion->cantidad;
                }
            }
        }

        if ($this->cantidadSeleccionada > $cantidadDisponible) {
            $this->setMensaje('No hay suficiente stock para este item', 'error');
            return;
        }

        $cantidadRestante = $this->cantidadSeleccionada;
        $detalleTemporal = [];

        foreach ($modelo->existencias as $existencia) {
            $lotes = $existencia->reposiciones()
                ->where('cantidad', '>', 0)
                ->where('estado_revision', 1)
                ->orderBy('created_at')
                ->get();

            foreach ($lotes as $lote) {
                if ($cantidadRestante <= 0)
                    break;

                $consumir = min($cantidadRestante, $lote->cantidad);

                $detalleTemporal[] = [
                    'existencia_id' => $existencia->id,
                    'reposicion_id' => $lote->id,
                    'cantidad' => $consumir,
                    'nombre' => $descripcion,
                    'tipo' => $this->tipoProducto,
                    'nuevo' => true,
                ];

                $cantidadRestante -= $consumir;
            }
            if ($cantidadRestante <= 0)
                break;
        }

        $this->detalles = array_merge($this->detalles, $detalleTemporal);
        $this->setMensaje('Item agregado correctamente', 'success');
        $this->productoSeleccionado = null;
        $this->otroSeleccionado = null;
        $this->cantidadSeleccionada = null;
        $this->tipoProducto = 'producto';
    }
    public function eliminarDetalle($index)
    {
        $pd = $this->detalles[$index];

        if (isset($pd['id'])) {
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
            'cliente_id' => 'nullable',
            'personal_id' => 'nullable',
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

        foreach ($this->detalles as $index => $pd) {
            if (isset($pd['id']) && ($pd['eliminar'] ?? false)) {
                $detalle = PedidoDetalle::find($pd['id']);
                if ($detalle) {
                    $lote = Reposicion::find($detalle->reposicion_id);
                    if ($lote) {
                        $lote->cantidad += $detalle->cantidad;
                        $lote->save();
                    }
                    $detalle->delete();
                }
                unset($this->detalles[$index]);
            }
        }

        foreach ($this->detalles as $pd) {
            if (!isset($pd['id']) || ($pd['nuevo'] ?? false)) {
                $detalle = PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'existencia_id' => $pd['existencia_id'],
                    'reposicion_id' => $pd['reposicion_id'],
                    'cantidad' => $pd['cantidad'],
                ]);

                $lote = Reposicion::find($pd['reposicion_id']);
                if ($lote) {
                    $lote->cantidad -= $pd['cantidad'];
                    $lote->save();
                }
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
        $productos = Producto::whereHas('existencias', function ($q) {
            if ($this->sucursal_id) {
                $q->where('sucursal_id', $this->sucursal_id);
            }
            $q->whereHas('reposiciones', function ($query) {
                $query->where('estado_revision', 1)
                    ->where('cantidad', '>', 0);
            });
        })->with([
                    'existencias.reposiciones' => function ($query) {
                        $query->where('estado_revision', 1)
                            ->where('cantidad', '>', 0);
                    }
                ])->get();

        $otros = Otro::whereHas('existencias', function ($q) {
            if ($this->sucursal_id) {
                $q->where('sucursal_id', $this->sucursal_id);
            }
            $q->whereHas('reposiciones', function ($query) {
                $query->where('estado_revision', 1)
                    ->where('cantidad', '>', 0);
            });
        })->with([
                    'existencias.reposiciones' => function ($query) {
                        $query->where('estado_revision', 1)
                            ->where('cantidad', '>', 0);
                    }
                ])->get();

        return view('livewire.pedidos', [
            'pedidos' => Pedido::with(['cliente', 'personal', 'detalles'])->latest()->get(),
            'productos' => $productos,
            'otros' => $otros,
            'detalles' => $this->detalles,
            'sucursales' => \App\Models\Sucursal::orderBy('nombre')->get(),
        ]);
    }

    public function filtrarSucursalModal($id = null)
    {
        $this->sucursal_id = $id;
    }


    public function abrirModalPagosPedido($pedido_id)
    {
        $this->pedidoParaPago = $pedido_id;
        $this->pagos = PagoPedido::where('pedido_id', $pedido_id)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'codigo' => $p->codigo ?? 'PAGO-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'fecha_pago' => $p->fecha_pago ? Carbon::parse($p->fecha_pago)->format('Y-m-d') : now()->format('Y-m-d'),
                'monto' => $p->monto,
                'observaciones' => $p->observaciones,
                'imagen_comprobante' => $p->imagen_comprobante,
                'metodo' => $p->metodo,
                'referencia' => $p->referencia,
                'estado' => $p->estado,
            ])->toArray();

        $this->modalPagos = true;
    }

    public function agregarPagoPedido()
    {
        $this->pagos[] = [
            'id' => null,
            'codigo' => 'PAGO-' . now()->format('Ymd') . '-' . str_pad(count($this->pagos) + 1, 3, '0', STR_PAD_LEFT),
            'fecha_pago' => now()->format('Y-m-d'),
            'monto' => null,
            'observaciones' => null,
            'imagen_comprobante' => null,
            'metodo' => null,
            'referencia' => null,
            'estado' => 0,
        ];
    }

    public function eliminarPagoPedido($index)
    {
        $pago = $this->pagos[$index] ?? null;
        if ($pago && isset($pago['id']) && $pago['id']) {
            PagoPedido::find($pago['id'])?->delete();
        }
        unset($this->pagos[$index]);
        $this->pagos = array_values($this->pagos);
    }

    public function guardarPagosPedido()
    {
        foreach ($this->pagos as $index => $pago) {
            $imagenPath = $pago['imagen_comprobante'];
            if ($imagenPath instanceof \Illuminate\Http\UploadedFile) {
                $imagenPath = $pago['imagen_comprobante']->store('pagos_pedido', 'public');
            }

            PagoPedido::updateOrCreate(
                ['id' => $pago['id'] ?? 0],
                [
                    'pedido_id' => $this->pedidoParaPago,
                    'codigo' => $pago['codigo'],
                    'monto' => $pago['monto'],
                    'fecha_pago' => $pago['fecha_pago'] ?? now()->format('Y-m-d'),
                    'observaciones' => $pago['observaciones'] ?? null,
                    'imagen_comprobante' => $imagenPath,
                    'metodo' => $pago['metodo'] ?? null,
                    'referencia' => $pago['referencia'] ?? null,
                    'estado' => $pago['estado'] ?? 0,

                ]
            );
        }

        $this->reset(['pagos']);
        $this->modalPagos = false;
    }

    public function abrirModalDetallePedido($pedido_id)
    {
        $this->pedidoDetalle = Pedido::with(['cliente', 'personal', 'detalles.existencia.existenciable', 'detalles.existencia.sucursal'])
            ->find($pedido_id);

        $this->modalDetallePedido = true;
    }
}
