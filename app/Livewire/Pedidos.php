<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Producto;
use App\Models\Otro;
use App\Models\Reposicion;
use App\Models\SolicitudPedido;
use App\Models\Sucursal;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class Pedidos extends Component
{
    use WithFileUploads;
    public $cliente_id = null;
    public $cantidad = 50;
    public $observaciones;
    public $sucursal_id = null;
    public $modalDetallePedido = false;
    public $pedidoDetalle;
    public $search = '';
    public $imagenPreviewModal = null;
    public $pedido;
    public $solicitud_pedido_id;
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
    public $solicitudSeleccionadaId = null;
    public $modalEliminarPedido = false;
    public $pedidoAEliminar = null;
    public $eliminarSolicitudAsociada = false;

    public function quitarSolicitud()
    {
        $this->solicitudSeleccionadaId = null;
        $this->solicitud_pedido_id = null;

        $this->setMensaje('Solicitud eliminada del pedido, los detalles se conservan', 'success');
    }

    public function mount($pedido_id = null)
    {
        $this->pedido = $pedido_id ? Pedido::find($pedido_id) : new Pedido();
        $this->personal_id = $this->pedido->personal_id ?? Auth::id();
        $this->fecha_pedido = $this->pedido->fecha_pedido ? Carbon::parse($this->pedido->fecha_pedido) : now();
        $this->solicitud_pedido_id = $this->pedido->solicitud_pedido_id;
        $this->cliente_id = $this->pedido->cliente_id ?? null;
        $this->solicitudSeleccionadaId = $this->pedido->solicitud_pedido_id;
        $this->estado_pedido = $this->pedido->estado_pedido ?? 0;
        $this->observaciones = $this->pedido->observaciones ?? null;

        if ($this->pedido->exists && $this->pedido->detalles->count()) {
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
        }
    }
    public function render()
    {
        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;
        $sucursalId = $personal->trabajos()->latest()->first()?->sucursal_id;
        $productos = Producto::whereHas('existencias', function ($q) use ($rol, $sucursalId) {
            if ($this->sucursal_id) {
                $q->where('sucursal_id', $this->sucursal_id);
            } elseif (in_array($rol, [2, 3]) && $sucursalId) {
                $q->where('sucursal_id', $sucursalId);
            }
            $q->whereHas(
                'reposiciones',
                fn($query) =>
                $query->where('estado_revision', 1)->where('cantidad', '>', 0)
            );
        })->with([
            'existencias' => function ($q) use ($rol, $sucursalId) {
                if ($this->sucursal_id) {
                    $q->where('sucursal_id', $this->sucursal_id);
                } elseif (in_array($rol, [2, 3]) && $sucursalId) {
                    $q->where('sucursal_id', $sucursalId);
                }
                $q->whereHas(
                    'reposiciones',
                    fn($query) =>
                    $query->where('estado_revision', 1)->where('cantidad', '>', 0)
                )->with('sucursal', 'reposiciones');
            }
        ])->get();
        $otros = Otro::whereHas('existencias', function ($q) use ($rol, $sucursalId) {
            if ($this->sucursal_id) {
                $q->where('sucursal_id', $this->sucursal_id);
            } elseif (in_array($rol, [2, 3]) && $sucursalId) {
                $q->where('sucursal_id', $sucursalId);
            }
            $q->whereHas(
                'reposiciones',
                fn($query) =>
                $query->where('estado_revision', 1)->where('cantidad', '>', 0)
            );
        })->with([
            'existencias' => function ($q) use ($rol, $sucursalId) {
                if ($this->sucursal_id) {
                    $q->where('sucursal_id', $this->sucursal_id);
                } elseif (in_array($rol, [2, 3]) && $sucursalId) {
                    $q->where('sucursal_id', $sucursalId);
                }
                $q->whereHas(
                    'reposiciones',
                    fn($query) =>
                    $query->where('estado_revision', 1)->where('cantidad', '>', 0)
                )->with('sucursal', 'reposiciones');
            }
        ])->get();

        $solicitudPedidos = SolicitudPedido::with([
            'cliente',
            'detalles.producto.existencias.sucursal',
            'detalles.otro.existencias.sucursal',
            'detalles.tapa',
            'detalles.etiqueta'
        ])->whereDoesntHave('pedido')
            ->orderBy('created_at', 'desc')
            ->get();

        $solicitudPedidos->each(function ($solicitud) {
            $solicitud->detalles->each(function ($detalle) {
                $item = $detalle->producto ?? $detalle->otro;
                $existencia = $item->existencias->first();
                $detalle->sucursal_nombre = $existencia?->sucursal->nombre ?? 'Sin sucursal';
            });
        });

        if ($this->pedido->exists && $this->pedido->solicitud_pedido_id) {
            $solicitudEdit = SolicitudPedido::with([
                'cliente',
                'detalles.producto.existencias.sucursal',
                'detalles.otro.existencias.sucursal',
                'detalles.tapa',
                'detalles.etiqueta'
            ])->find($this->pedido->solicitud_pedido_id);

            if ($solicitudEdit) {
                $solicitudEdit->detalles->each(function ($detalle) {
                    $item = $detalle->producto ?? $detalle->otro;
                    $existencia = $item->existencias->first();
                    $detalle->sucursal_nombre = $existencia?->sucursal->nombre ?? 'Sin sucursal';
                });

                if (!$solicitudPedidos->contains('id', $solicitudEdit->id)) {
                    $solicitudPedidos->prepend($solicitudEdit);
                }
            }
        }
        $pedidos = Pedido::with(['solicitudPedido.cliente', 'personal', 'detalles.existencia.sucursal'])
            ->when($this->search, function ($q) {
                $q->where('codigo', 'like', '%' . $this->search . '%')
                    ->orWhereHas(
                        'solicitudPedido.cliente',
                        fn($c) =>
                        $c->where('nombre', 'like', '%' . $this->search . '%')
                    );
            })
            ->when($rol === 3, function ($q) use ($personal, $sucursalId) {
                $q->where('personal_id', $personal->id)
                    ->whereHas(
                        'detalles.existencia',
                        fn($e) =>
                        $e->where('sucursal_id', $sucursalId)
                    );
            })
            ->when($rol === 2, function ($q) use ($sucursalId) {
                $q->whereHas(
                    'detalles.existencia',
                    fn($e) =>
                    $e->where('sucursal_id', $sucursalId)
                );
            })
            ->latest()
            ->take($this->cantidad)
            ->get();



        $clientes = Cliente::orderBy('nombre')->get();

        return view('livewire.pedidos', [
            'pedidos' => $pedidos,
            'productos' => $productos,
            'otros' => $otros,
            'detalles' => $this->detalles,
            'sucursales' => Sucursal::orderBy('nombre')->get(),
            'solicitudPedidos' => $solicitudPedidos,
            'clientes' => $clientes,
            'sucursalId' => null,
        ]);
    }

    public function cargarMas()
    {
        $this->cantidad += 50;
    }

    public function cargarMenos()
    {
        if ($this->cantidad > 50) {
            $this->cantidad -= 50;
        }
    }

    public function abrirModal()
    {
        $this->modalPedido = true;
    }
    public function cerrarModal()
    {
        $this->modalPedido = false;
        $this->detalles = [];
        $this->productoSeleccionado = null;
        $this->otroSeleccionado = null;
        $this->cantidadSeleccionada = null;
        $this->solicitud_pedido_id = null;
        $this->solicitudSeleccionadaId = null;
        $this->personal_id = null;
        $this->estado_pedido = 0;
        $this->cliente_id = null;
        $this->pedido = new Pedido();
    }
    public function seleccionarSolicitud($id = null)
    {
        $this->solicitudSeleccionadaId = $id;
        $this->solicitud_pedido_id = $id;

        if ($id) {
            $solicitud = SolicitudPedido::with('cliente')->find($id);
            $this->cliente_id = $solicitud?->cliente_id;
        } else {
            $this->detalles = [];
            $this->cliente_id = null;
        }
    }
    public function agregarProducto()
    {
        if (!$this->cantidadSeleccionada)
            return $this->setMensaje('Debe seleccionar una cantidad', 'error');
        if ($this->tipoProducto === 'producto' && !$this->productoSeleccionado)
            return $this->setMensaje('Debe seleccionar un producto', 'error');
        if ($this->tipoProducto === 'otro' && !$this->otroSeleccionado)
            return $this->setMensaje('Debe seleccionar un item', 'error');

        $modelo = $this->tipoProducto === 'producto'
            ? Producto::with('existencias.reposiciones')->find($this->productoSeleccionado)
            : Otro::with('existencias.reposiciones')->find($this->otroSeleccionado);

        if (!$modelo)
            return $this->setMensaje('Item no existe', 'error');

        $cantidadDisponible = 0;
        foreach ($modelo->existencias as $existencia) {
            foreach ($existencia->reposiciones as $reposicion) {
                if ($reposicion->estado_revision == 1 && $reposicion->cantidad > 0) {
                    $cantidadDisponible += $reposicion->cantidad;
                }
            }
        }

        if ($this->cantidadSeleccionada > $cantidadDisponible)
            return $this->setMensaje('No hay suficiente stock', 'error');

        $cantidadRestante = $this->cantidadSeleccionada;
        $detalleTemporal = [];
        foreach ($modelo->existencias as $existencia) {
            $lotes = $existencia->reposiciones()
                ->where('cantidad', '>', 0)
                ->where('estado_revision', 1)
                ->orderBy('created_at')->get();

            foreach ($lotes as $lote) {
                if ($cantidadRestante <= 0)
                    break;
                $consumir = min($cantidadRestante, $lote->cantidad);
                $detalleTemporal[] = [
                    'existencia_id' => $existencia->id,
                    'reposicion_id' => $lote->id,
                    'cantidad' => $consumir,
                    'nombre' => $modelo->descripcion ?? 'Sin nombre',
                    'tipo' => $this->tipoProducto,
                    'nuevo' => true,
                    'sucursal_nombre' => $existencia->sucursal->nombre ?? 'Sin sucursal',
                    'tipo_contenido' => $modelo->tipoContenido ?? null,
                ];
                $cantidadRestante -= $consumir;
            }
            if ($cantidadRestante <= 0)
                break;
        }

        $this->detalles = array_merge($this->detalles, $detalleTemporal);
        $this->productoSeleccionado = null;
        $this->otroSeleccionado = null;
        $this->cantidadSeleccionada = null;
        $this->tipoProducto = 'producto';
        $this->setMensaje('Item agregado correctamente', 'success');
    }
    private function setMensaje($texto, $tipo = 'success')
    {
        $this->mensaje = $texto;
        $this->tipoMensaje = $tipo;
    }
    public function filtrarSucursalModal($id = null)
    {
        $this->sucursal_id = $id;
    }
    public function editarPedido($pedido_id)
    {
        $this->pedido = Pedido::with([
            'detalles.existencia.existenciable',
            'detalles.existencia.sucursal',
            'solicitudPedido'
        ])->find($pedido_id);

        $this->solicitud_pedido_id = $this->pedido->solicitud_pedido_id;
        $this->solicitudSeleccionadaId = $this->pedido->solicitud_pedido_id;
        $this->personal_id = $this->pedido->personal_id;
        $this->estado_pedido = $this->pedido->estado_pedido;
        $this->fecha_pedido = $this->pedido->fecha_pedido ?? now();
        $this->observaciones = $this->pedido->observaciones;
        $this->cliente_id = $this->pedido->cliente_id;

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
                'tipo_contenido' => $existenciable->tipoContenido ?? null,
            ];
        })->toArray();

        $this->modalPedido = true;
    }
    public function abrirModalDetallePedido($pedido_id)
    {
        $this->pedidoDetalle = Pedido::with([
            'solicitudPedido.cliente',
            'personal',
            'detalles.existencia.existenciable',
            'detalles.existencia.sucursal'
        ])->find($pedido_id);

        $this->modalDetallePedido = true;
    }
    public function eliminarDetalle($index)
    {
        $detalle = $this->detalles[$index];

        if (!empty($detalle['ultimo_por_sucursal'])) {
            $this->setMensaje('No se puede eliminar el último ítem de la sucursal', 'error');
            return;
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
        if ($this->solicitud_pedido_id) {
            $this->validate(['solicitud_pedido_id' => 'exists:solicitud_pedidos,id']);
            $solicitud = SolicitudPedido::find($this->solicitud_pedido_id);
            if ($solicitud->pedido && !$this->pedido->exists) {
                $this->setMensaje('Esta solicitud ya tiene un pedido asociado', 'error');
                return;
            }
        }

        $detallesActivos = array_values(array_filter($this->detalles, function ($d) {
            return !isset($d['eliminar']) || !$d['eliminar'];
        }));

        if (empty($detallesActivos)) {
            $this->setMensaje('No se puede guardar un pedido sin ítems', 'error');
            return;
        }

        $pedido = $this->pedido;
        if (!$pedido->exists) {
            $pedido->personal_id = $this->personal_id ?? Auth::id();
        }

        $pedido->solicitud_pedido_id = $this->solicitud_pedido_id ?? null;
        $pedido->estado_pedido = $this->estado_pedido;
        $pedido->fecha_pedido = $pedido->fecha_pedido ?? $this->fecha_pedido ?? now();
        $pedido->observaciones = $this->observaciones;
        $pedido->cliente_id = $this->cliente_id;


        if (!$pedido->exists) {
            do {
                $codigo = 'P-' . now()->format('YmdHis') . '-' . rand(100, 999);
            } while (Pedido::where('codigo', $codigo)->exists());
            $pedido->codigo = $codigo;
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

        foreach ($detallesActivos as $pd) {
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
    public function eliminarPedido($pedido_id, $eliminarSolicitud = false)
    {
        $pedido = Pedido::with('detalles')->find($pedido_id);

        if (!$pedido) {
            return $this->setMensaje('Pedido no encontrado', 'error');
        }
        foreach ($pedido->detalles as $detalle) {
            if ($detalle->reposicion_id) {
                $lote = Reposicion::find($detalle->reposicion_id);
                if ($lote) {
                    $lote->cantidad += $detalle->cantidad;
                    $lote->save();
                }
            }
            $detalle->delete();
        }
        $solicitud_id = $pedido->solicitud_pedido_id;
        $pedido->delete();
        if ($eliminarSolicitud && $solicitud_id) {
            $solicitud = SolicitudPedido::find($solicitud_id);
            if ($solicitud) {
                $solicitud->delete();
            }
        }

        $this->setMensaje('Pedido eliminado correctamente', 'success');
    }
    public function confirmarEliminarPedido($pedido_id, $eliminarSolicitud = false)
    {
        $this->pedidoAEliminar = $pedido_id;
        $this->eliminarSolicitudAsociada = $eliminarSolicitud;
        $this->modalEliminarPedido = true;
    }
    public function eliminarPedidoConfirmado()
    {
        if ($this->pedidoAEliminar) {
            $this->eliminarPedido($this->pedidoAEliminar, $this->eliminarSolicitudAsociada);
        }

        $this->modalEliminarPedido = false;
        $this->pedidoAEliminar = null;
        $this->eliminarSolicitudAsociada = false;
    }
}
