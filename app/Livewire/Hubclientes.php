<?php

namespace App\Livewire;

use App\Models\Otro;
use App\Models\Producto;
use App\Models\Tapa;
use App\Models\Etiqueta;
use App\Models\SolicitudPedido;
use App\Models\SolicitudPedidoDetalle;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Hubclientes extends Component
{
    public $productos = [];
    public $carrito = [];
    public $cantidades = [];
    public $modalPedidosCliente = false;
    public $pedidosCliente = [];
    public $mostrarCarrito = false;
    public $modalProducto = false;
    public $productoSeleccionado = null;
    public $tapas = [];
    public $etiquetas = [];
    public $tapaSeleccionada = null;
    public $etiquetaSeleccionada = null;
    public $cantidadSeleccionada = 1;

    public function mount()
    {
        // Cargar productos y otros
        $productos = Producto::where('estado', 1)
            ->whereHas('existencias')
            ->with('existencias')
            ->get()
            ->groupBy(fn($p) => $p->descripcion . '|' . $p->tipoProducto . '|' . $p->capacidad)
            ->map(fn($g) => $g->first())
            ->map(fn($p) => [
                'uid' => 'producto_' . $p->id,
                'id' => $p->id,
                'descripcion' => $p->descripcion,
                'precio' => $p->precioReferencia,
                'imagen' => $p->imagen,
                'paquete' => $p->paquete ?? 1,
                'tipoProducto' => $p->tipoProducto,
                'capacidad' => $p->capacidad,
                'unidad' => $p->unidad,
                'tipo_modelo' => 'producto',
                'sucursales' => $p->existencias->pluck('sucursal_id')->toArray(),
                'existencias' => $p->existencias->toArray(),
            ])
            ->values()
            ->toArray();

        $otros = Otro::where('estado', 1)
            ->whereHas('existencias')
            ->with('existencias')
            ->get()
            ->groupBy(fn($o) => $o->descripcion . '|' . $o->tipoProducto . '|' . $o->capacidad)
            ->map(fn($g) => $g->first())
            ->map(fn($o) => [
                'uid' => 'otro_' . $o->id,
                'id' => $o->id,
                'descripcion' => $o->descripcion,
                'precio' => $o->precioReferencia,
                'imagen' => $o->imagen,
                'paquete' => $o->paquete ?? 1,
                'capacidad' => $o->capacidad,
                'unidad' => $o->unidad,
                'tipoContenido' => $o->tipoContenido,
                'tipoProducto' => $o->tipoProducto,
                'tipo_modelo' => 'otro',
                'sucursales' => $o->existencias->pluck('sucursal_id')->toArray(),
                'existencias' => $o->existencias->toArray(),
            ])
            ->values()
            ->toArray();

        $this->productos = array_merge($productos, $otros);
    }

    public function abrirModalProducto($uid)
{
    $item = collect($this->productos)->firstWhere('uid', $uid);
    if (!$item) return;

    // Tomar imagen de la primera existencia con imagen
    $existencia = $item['existencias'] ?? [];
    $imagenSucursal = null;
    foreach ($existencia as $e) {
        if (!empty($e['imagen'])) {
            $imagenSucursal = $e['imagen'];
            break;
        }
    }
    $item['imagen'] = $imagenSucursal ?? $item['imagen'];
    $this->productoSeleccionado = $item;

    // Cargar tapas únicas, priorizando las que tengan imagen
    $this->tapas = Tapa::where('estado', 1)
        ->orderBy('descripcion')
        ->get()
        ->groupBy('descripcion')
        ->map(function ($grupo) {
            return $grupo->first(fn($t) => !empty($t->imagen)) ?? $grupo->first();
        })
        ->values();

    // Cargar etiquetas únicas, priorizando las que tengan imagen
    $this->etiquetas = Etiqueta::where('estado', 1)
        ->orderBy('descripcion')
        ->get()
        ->groupBy('descripcion')
        ->map(function ($grupo) {
            return $grupo->first(fn($e) => !empty($e->imagen)) ?? $grupo->first();
        })
        ->values();

    // Inicializar selección
    $this->tapaSeleccionada = null;
    $this->etiquetaSeleccionada = null;
    $this->cantidadSeleccionada = 1;
    $this->modalProducto = true;
}


    public function agregarAlCarritoDesdeModal()
    {
        if (!$this->productoSeleccionado) return;

        $uid = $this->productoSeleccionado['uid'] . '_' . ($this->tapaSeleccionada ?? '0') . '_' . ($this->etiquetaSeleccionada ?? '0');
        $cantidad = $this->cantidadSeleccionada;
        $item = $this->productoSeleccionado;

        $tapa = $this->tapaSeleccionada ? Tapa::find($this->tapaSeleccionada) : null;
        $etiqueta = $this->etiquetaSeleccionada ? Etiqueta::find($this->etiquetaSeleccionada) : null;

        $this->carrito[$uid] = [
            'uid' => $uid,
            'id' => $item['id'],
            'descripcion' => $item['descripcion'],
            'precio' => $item['precio'],
            'cantidad' => $cantidad,
            'imagen' => $item['imagen'],
            'tipo_modelo' => $item['tipo_modelo'],
            'paquete' => $item['paquete'] ?? 1,
            'tapa_descripcion' => $tapa->descripcion ?? null,
            'tapa_imagen' => $tapa->imagen ?? null,
            'etiqueta_descripcion' => $etiqueta->descripcion ?? null,
            'etiqueta_imagen' => $etiqueta->imagen ?? null,
        ];

        $this->modalProducto = false;
        $this->productoSeleccionado = null;
        $this->tapaSeleccionada = null;
        $this->etiquetaSeleccionada = null;
        $this->cantidadSeleccionada = 1;
    }

    public function hacerPedido()
    {
        $clienteId = Auth::user()->cliente->id ?? null;
        if (!$clienteId || empty($this->carrito)) return;

        $solicitud = SolicitudPedido::create([
            'cliente_id' => $clienteId,
            'codigo' => 'SP-' . now()->format('YmdHis'),
            'estado' => 0,
            'observaciones' => null,
        ]);

        foreach ($this->carrito as $item) {
            $cantidad = $item['cantidad'];
            $paquete = $item['paquete'] ?? 1;
            $precio_unitario = $item['precio'];
            $total = $cantidad * $paquete * $precio_unitario;

            SolicitudPedidoDetalle::create([
                'solicitud_pedido_id' => $solicitud->id,
                'descripcion' => $item['descripcion'],
                'cantidad' => $cantidad,
                'paquete' => $paquete,
                'precio_unitario' => $precio_unitario,
                'total' => $total,
                'tapa_descripcion' => $item['tapa_descripcion'] ?? null,
                'tapa_imagen' => $item['tapa_imagen'] ?? null,
                'etiqueta_descripcion' => $item['etiqueta_descripcion'] ?? null,
                'etiqueta_imagen' => $item['etiqueta_imagen'] ?? null,
            ]);
        }

        $this->carrito = [];
        $this->mostrarCarrito = false;
        $this->verMisPedidos();
    }

    public function verMisPedidos()
    {
        $cliente = Auth::user()->cliente ?? null;
        if (!$cliente) return;

        $this->pedidosCliente = SolicitudPedido::where('cliente_id', $cliente->id)
            ->with('detalles')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($pedido) {
                $detalles = $pedido->detalles->map(function ($det) {
                    $detArray = $det->toArray();

                    // Preparar array de etiquetas con imagen para mostrar
                    $detArray['etiquetas_info'] = [];
                    if (!empty($det->etiqueta_descripcion)) {
                        $descs = explode('|', $det->etiqueta_descripcion);
                        $imgs  = explode('|', $det->etiqueta_imagen ?? '');
                        foreach ($descs as $i => $desc) {
                            $detArray['etiquetas_info'][] = [
                                'descripcion' => $desc,
                                'imagen' => $imgs[$i] ?? null,
                            ];
                        }
                    }

                    // Tapa como info aparte
                    if (!empty($det->tapa_descripcion)) {
                        $detArray['tapa_info'] = [
                            'descripcion' => $det->tapa_descripcion,
                            'imagen' => $det->tapa_imagen ?? null,
                        ];
                    }

                    return $detArray;
                });

                $pedidoArray = $pedido->toArray();
                $pedidoArray['detalles'] = $detalles;
                return $pedidoArray;
            })
            ->toArray();

        $this->modalPedidosCliente = true;
    }

    public function cerrarModalPedidos()
    {
        $this->modalPedidosCliente = false;
    }

    public function eliminarDelCarrito($uid)
    {
        unset($this->carrito[$uid]);
    }

    public function eliminarSolicitud($id)
    {
        $pedido = SolicitudPedido::find($id);
        if (!$pedido) return;

        $pedido->detalles()->delete();
        $pedido->delete();

        $this->pedidosCliente = collect($this->pedidosCliente)->filter(fn($p) => $p['id'] !== $id)->values()->toArray();
        $this->modalPedidosCliente = false;
    }

    public function render()
    {
        return view('livewire.hubclientes', [
            'productos' => $this->productos,
            'carrito' => $this->carrito,
            'pedidosCliente' => $this->pedidosCliente,
        ]);
    }
}
