<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Distribucion;
use App\Models\PagoPedido;
use App\Models\SucursalPago;
use Carbon\Carbon;

class Pedidospersonal extends Component
{
    use WithFileUploads;

    public $search = '';

    public $modalPagos = false;
    public $pedidoParaPago = null;
    public $pagos = [];
    public $sucursalesPago = [];
    public $imagenPreviewModal = null;
    public $modalImagenAbierta = false;

    protected $rules = [
        'pagos.*.monto' => 'required|numeric|min:0.01',
    ];

    protected $messages = [
        'pagos.*.monto.required' => 'El campo monto es obligatorio.',
        'pagos.*.monto.numeric' => 'El monto debe ser un número.',
        'pagos.*.monto.min' => 'El monto debe ser mayor que cero.',
    ];

    public function abrirModalPagosPedido($pedido_id)
    {
        $this->pedidoParaPago = $pedido_id;

        $this->sucursalesPago = SucursalPago::where('estado', 1)->get();

        $this->pagos = PagoPedido::where('pedido_id', $pedido_id)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'codigo_pago' => $p->codigo_pago ?? 'PAGO-' . now()->format('YmdHis') . '-' . rand(100, 999),
                'fecha_pago' => $p->fecha_pago ? Carbon::parse($p->fecha_pago)->format('Y-m-d') : now()->format('Y-m-d'),
                'monto' => $p->monto,
                'observaciones' => $p->observaciones,
                'imagen_comprobante' => $p->imagen_comprobante,
                'metodo' => $p->metodo,
                'referencia' => $p->referencia,
                'estado' => $p->estado,
                'sucursal_pago_id' => $p->sucursal_pago_id,
            ])
            ->toArray();

        $this->modalPagos = true;
    }

    public function agregarPagoPedido()
    {
        $this->pagos[] = [
            'id' => null,
            'codigo_pago' => 'PAGO-' . now()->format('YmdHis') . '-' . rand(100, 999),
            'fecha_pago' => now()->format('Y-m-d'),
            'monto' => null,
            'observaciones' => null,
            'imagen_comprobante' => null,
            'metodo' => null,
            'referencia' => null,
            'estado' => 0,
            'sucursal_pago_id' => null,
        ];
    }

    public function guardarPagosPedido()
    {
        $this->validate();

        foreach ($this->pagos as $pago) {
            $imagenPath = $pago['imagen_comprobante'] ?? null;

            if ($imagenPath instanceof \Illuminate\Http\UploadedFile) {
                $imagenPath = $imagenPath->store('pagos_pedido', 'public');
            }

            PagoPedido::updateOrCreate(
                ['id' => $pago['id'] ?? 0],
                [
                    'pedido_id' => $this->pedidoParaPago,
                    'codigo_pago' => $pago['codigo_pago'] ?? 'PAGO-' . now()->format('YmdHis') . '-' . rand(100, 999),
                    'monto' => $pago['monto'],
                    'fecha_pago' => $pago['fecha_pago'] ?? now()->format('Y-m-d'),
                    'observaciones' => $pago['observaciones'] ?? null,
                    'imagen_comprobante' => $imagenPath,
                    'metodo' => isset($pago['metodo']) ? (int) $pago['metodo'] : 0,
                    'referencia' => $pago['referencia'] ?? null,
                    'estado' => isset($pago['estado']) ? (bool) $pago['estado'] : false,
                    'sucursal_pago_id' => $pago['sucursal_pago_id'] ?? null,
                ]
            );
        }
        $this->modalPagos = false;
        $this->pagos = [];
        $this->pedidoParaPago = null;
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

    public function render()
    {
        $usuario = auth()->user();
        $personalId = optional($usuario->personal)->id;

        if (!$personalId || $usuario->rol_id != 3) {
            return view('livewire.pedidospersonal', [
                'distribuciones' => collect()
            ]);
        }

        $search = $this->search;

        $query = Distribucion::with([
            'coche',
            'pedidos.solicitudPedido.cliente'
        ])
            ->where('personal_id', $personalId)
            ->whereHas('pedidos', function ($p) use ($search) {
                $p->where('codigo', 'like', "%{$search}%")
                    ->orWhereHas('solicitudPedido', function ($s) use ($search) {
                        $s->where('codigo', 'like', "%{$search}%")
                            ->orWhereHas('cliente', function ($c) use ($search) {
                                $c->where('nombre', 'like', "%{$search}%");
                            });
                    });
            })
            ->latest();

        return view('livewire.pedidospersonal', [
            'distribuciones' => $query->get()
        ]);
    }

    public function cambiarEstadoPedido($pedidoId, $nuevoEstado)
    {
        $pedido = \App\Models\Pedido::find($pedidoId);

        if ($pedido) {
            $pedido->estado_pedido = $nuevoEstado;
            $pedido->save();
        }
    }

}
