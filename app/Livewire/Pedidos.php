<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Existencia;
use App\Models\Reposicion;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class Pedidos extends Component
{
    public $modal = false;
    public $accion = 'create';
    public $pedido_id;
    public $codigo;
    public $cliente_id;
    public $personal_id;
    public $personal_nombres;
    public $personal_apellidos;
    public $estado_pedido = 0;
    public $fecha_pedido;
    public $observaciones;
    public $productos = [];
    public $existencias = [];
    public $clientes = [];
    public $modalError = false;
    public $mensajeError = '';
    public $estado = 0;
    public $confirmingDeletePedidoId = null;
    public $modalDetalle = false;
    public $detallePedido;

    protected $rules = [
        'cliente_id' => 'required|exists:clientes,id',
        'productos' => 'required|array|min:1',
        'productos.*.existencia_id' => 'required|exists:existencias,id',
        'productos.*.cantidad' => 'required|integer|min:1',
    ];

    protected $messages = [
        'cliente_id.required' => 'Debe seleccionar un cliente.',
        'productos.required' => 'Debe agregar al menos un producto.',
        'productos.*.existencia_id.required' => 'Debe seleccionar un producto.',
        'productos.*.cantidad.required' => 'Debe ingresar una cantidad válida.',
        'productos.*.cantidad.min' => 'La cantidad debe ser mayor a 0.',
    ];

    public function mount()
    {
        $this->recargarDatosBase();
    }

    private function recargarDatosBase()
    {
        $this->existencias = Existencia::with([
            'existenciable',
            'reposiciones' => fn($q) => $q->where('estado_revision', 1)
        ])
        ->whereHas('reposiciones', fn($q) => $q->where('estado_revision', 1))
        ->where('cantidad', '>', 0)
        ->get();

        $this->clientes = Cliente::all();
        $this->fecha_pedido = now()->format('Y-m-d\TH:i');
        $this->codigo = 'PED-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'pedido_id',
            'codigo',
            'cliente_id',
            'observaciones',
            'productos',
            'personal_id',
            'personal_nombres',
            'personal_apellidos',
            'estado'
        ]);

        $this->accion = $accion;

        $usuario = auth()->user();
        $personal = $usuario->personal;

        if (!$personal) {
            $this->mensajeError = "No estás asignado a ningún personal válido.";
            $this->modalError = true;
            return;
        }

        $this->personal_id = $personal->id;
        $this->personal_nombres = $personal->nombres ?? $usuario->name ?? 'Sin personal';
        $this->personal_apellidos = $personal->apellidos ?? '';

        if ($accion === 'create') {
            $this->codigo = 'PED-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $this->fecha_pedido = now()->format('Y-m-d\TH:i');
            $this->estado = 0;
        }

        $queryExistencias = Existencia::with([
            'existenciable',
            'sucursal',
            'reposiciones' => fn($q) => $q->where('estado_revision', 1)
        ])
        ->whereHas('reposiciones', fn($q) => $q->where('estado_revision', 1))
        ->whereHas('existenciable', fn($q) => $q->where('estado', 1))
        ->orderBy('id');

        if ($usuario->rol_id === 2) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $queryExistencias->where('sucursal_id', $sucursal_id);
        }

        $this->existencias = $queryExistencias->get();
        $this->clientes = Cliente::all();

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $pedido = Pedido::with('detalles')->findOrFail($id);
        $this->pedido_id = $pedido->id;
        $this->codigo = $pedido->codigo;
        $this->cliente_id = $pedido->cliente_id;
        $this->fecha_pedido = $pedido->fecha_pedido;
        $this->estado_pedido = $pedido->estado_pedido;
        $this->observaciones = $pedido->observaciones;
        $this->productos = $pedido->detalles->map(fn($d) => [
            'existencia_id' => $d->existencia_id,
            'reposicion_id' => $d->reposicion_id,
            'cantidad' => $d->cantidad,
            'detalle_id' => $d->id,
        ])->toArray();
    }

    public function agregarProducto()
    {
        $this->productos[] = ['existencia_id' => null, 'cantidad' => 0];
    }

    public function eliminarProducto($index)
    {
        $producto = $this->productos[$index] ?? null;
        if (!$producto) return;

        if (isset($producto['detalle_id'])) {
            $detalle = PedidoDetalle::find($producto['detalle_id']);
            if ($detalle) {
                $reposicion = Reposicion::find($detalle->reposicion_id);
                if ($reposicion) {
                    $reposicion->cantidad += $detalle->cantidad;
                    $reposicion->save();
                }

                $existencia = Existencia::find($detalle->existencia_id);
                if ($existencia) {
                    $existencia->cantidad += $detalle->cantidad;
                    $existencia->save();
                }

                $detalle->delete();
            }
        }

        unset($this->productos[$index]);
        $this->productos = array_values($this->productos);
    }

    public function confirmarEliminarPedido($pedido_id)
    {
        $this->confirmingDeletePedidoId = $pedido_id;
    }

    public function eliminarPedidoConfirmado()
    {
        if (!$this->confirmingDeletePedidoId) return;

        $pedido = Pedido::with('detalles')->find($this->confirmingDeletePedidoId);
        if ($pedido) {
            foreach ($pedido->detalles as $detalle) {
                $existencia = Existencia::find($detalle->existencia_id);
                if ($existencia) {
                    $existencia->cantidad += $detalle->cantidad;
                    $existencia->save();
                }

                $reposicion = Reposicion::find($detalle->reposicion_id);
                if ($reposicion) {
                    $reposicion->cantidad += $detalle->cantidad;
                    $reposicion->save();
                }

                $detalle->delete();
            }

            $pedido->delete();
        }

        $this->confirmingDeletePedidoId = null;
    }

    public function eliminarProductoExistente($detalle_id)
    {
        $detalle = PedidoDetalle::find($detalle_id);
        if (!$detalle) return;

        $reposicion = Reposicion::find($detalle->reposicion_id);
        if ($reposicion) {
            $reposicion->cantidad += $detalle->cantidad;
            $reposicion->save();
        }

        $existencia = Existencia::find($detalle->existencia_id);
        if ($existencia) {
            $existencia->cantidad += $detalle->cantidad;
            $existencia->save();
        }

        $detalle->delete();

        $this->productos = array_filter($this->productos, fn($p) => !isset($p['detalle_id']) || $p['detalle_id'] != $detalle_id);
        $this->productos = array_values($this->productos);
    }

    public function guardarPedido()
    {
        $this->validate();

        $usuario = auth()->user();
        if (!$usuario || !$usuario->personal) {
            $this->mensajeError = 'El usuario actual no tiene un personal asignado.';
            $this->modalError = true;
            return;
        }

        $this->personal_id = $usuario->personal->id;

        try {
            DB::transaction(function () {

                $pedido = Pedido::updateOrCreate(
                    ['id' => $this->pedido_id],
                    [
                        'codigo' => $this->codigo,
                        'cliente_id' => $this->cliente_id,
                        'personal_id' => $this->personal_id,
                        'estado_pedido' => $this->estado_pedido,
                        'fecha_pedido' => $this->fecha_pedido,
                        'observaciones' => $this->observaciones,
                    ]
                );

                foreach ($this->productos as $producto) {
                    $this->procesarReposiciones($producto, $pedido->id);
                }
            });

            $this->cerrarModal();
            session()->flash('message', 'Pedido guardado correctamente!');
        } catch (\Exception $e) {
            $this->mensajeError = $e->getMessage();
            $this->modalError = true;
        }
    }

    private function procesarReposiciones($producto, $pedido_id)
    {
        if (isset($producto['detalle_id'])) {
            $detalle = PedidoDetalle::find($producto['detalle_id']);
            if (!$detalle) return;
            $diferencia = $producto['cantidad'] - $detalle->cantidad;
            if ($diferencia > 0) {
                $restante = $diferencia;
                $reposiciones = Reposicion::where('existencia_id', $producto['existencia_id'])
                    ->where('cantidad', '>', 0)
                    ->where('estado_revision', true)
                    ->orderBy('fecha')
                    ->get();

                foreach ($reposiciones as $repo) {
                    if ($restante <= 0) break;
                    $usar = min($restante, $repo->cantidad);
                    $detalle->cantidad += $usar;
                    $detalle->save();
                    $repo->cantidad -= $usar;
                    $repo->save();
                    $restante -= $usar;
                }

                if ($restante > 0) {
                    throw new \Exception("No hay suficiente stock para el producto {$producto['existencia_id']}.");
                }

                $existencia = Existencia::find($producto['existencia_id']);
                $existencia->cantidad -= $diferencia;
                $existencia->save();
            } elseif ($diferencia < 0) {
                $existencia = Existencia::find($producto['existencia_id']);
                $existencia->cantidad += abs($diferencia);
                $existencia->save();
                $detalle->cantidad = $producto['cantidad'];
                $detalle->save();
            }
        } else {
            $restante = $producto['cantidad'];
            $reposiciones = Reposicion::where('existencia_id', $producto['existencia_id'])
                ->where('cantidad', '>', 0)
                ->where('estado_revision', true)
                ->orderBy('fecha')
                ->get();
            foreach ($reposiciones as $repo) {
                if ($restante <= 0) break;
                $usar = min($restante, $repo->cantidad);
                PedidoDetalle::create([
                    'pedido_id' => $pedido_id,
                    'reposicion_id' => $repo->id,
                    'existencia_id' => $producto['existencia_id'],
                    'cantidad' => $usar,
                ]);
                $repo->cantidad -= $usar;
                $repo->save();
                $restante -= $usar;
            }

            if ($restante > 0) {
                throw new \Exception("No hay suficiente stock disponible para el producto {$producto['existencia_id']}.");
            }
            $existencia = Existencia::find($producto['existencia_id']);
            $existencia->cantidad -= $producto['cantidad'];
            $existencia->save();
        }
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['pedido_id', 'codigo', 'cliente_id', 'observaciones', 'productos', 'estado_pedido']);
        $this->recargarDatosBase();
    }

    public function modaldetalle($pedido_id)
    {
        $this->detallePedido = Pedido::with(['cliente', 'personal', 'detalles.existencia.existenciable'])
            ->find($pedido_id);

        if (!$this->detallePedido) {
            $this->mensajeError = 'Pedido no encontrado';
            $this->modalError = true;
            return;
        }

        $this->modalDetalle = true;
    }

    public function render()
    {
        $pedidos = Pedido::with(['cliente', 'personal', 'detalles.existencia.existenciable'])
            ->latest()->get();

        return view('livewire.pedidos', compact('pedidos'));
    }
}
