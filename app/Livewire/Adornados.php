<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Adornado;
use App\Models\Pedido;
use App\Models\Reposicion;
use App\Models\Etiqueta;
use Illuminate\Support\Facades\DB;

class Adornados extends Component
{

    public $solicitud_pedido_id;
    public $solicitudPedidos = [];
    public $detallesSolicitud = [];
    public $search = '';
    public $searchPedido = '';
    public $modal = false;
    public $modalDetalle = false;
    public $adornado_id = null;
    public $pedido_id;
    public $codigo;
    public $observaciones = '';
    public $reposicionesSeleccionadas = [];
    public $reposiciones = [];


    public $accion = 'create';

    protected $rules = [
        'pedido_id' => 'required|exists:pedidos,id',
        'codigo' => 'required|string|max:50|unique:adornados,codigo',
        'observaciones' => 'nullable|string|max:500',
        'reposicionesSeleccionadas' => 'array',
    ];

    public function render()
    {
        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;
        $sucursalId = $personal->trabajos()->latest()->first()?->sucursal_id;

        $adornados = Adornado::with(['pedido', 'reposiciones.existencia.existenciable', 'personal'])
            ->when($rol === 4, fn($q) => $q->whereHas('reposiciones.existencia', fn($q2) => $q2->where('sucursal_id', $sucursalId)))
            ->when($this->search, fn($q) => $q->where('codigo', 'like', "%{$this->search}%")
                ->orWhereHas('pedido', fn($q2) => $q2->where('codigo', 'like', "%{$this->search}%")))
            ->latest()
            ->get();

        $pedidosQuery = Pedido::with([
            'personal',
            'solicitudPedido',
            'solicitudPedido.cliente',
            'solicitudPedido.detalles',
            'solicitudPedido.detalles.producto',
            'solicitudPedido.detalles.tapa',
            'solicitudPedido.detalles.etiqueta',
            'solicitudPedido.detalles.otro',
        ]);

        if ($this->accion === 'edit' && $this->adornado_id) {
            $pedidosQuery->where(fn($q) => $q->whereDoesntHave('adornados')->orWhere('id', $this->pedido_id));
        } else {
            $pedidosQuery->whereDoesntHave('adornados');
        }

        $pedidosQuery->when($this->searchPedido, function ($query) {
            $query->where('codigo', 'like', '%' . $this->searchPedido . '%')
                ->orWhereHas('solicitudPedido.cliente', function ($q) {
                    $q->where('nombre', 'like', '%' . $this->searchPedido . '%');
                });
        });
        $pedidos = $pedidosQuery->get();

        return view('livewire.adornados', [
            'adornados' => $adornados,
            'pedidos' => $pedidos,
        ]);
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'adornado_id',
            'pedido_id',
            'codigo',
            'observaciones',
            'reposicionesSeleccionadas',
            'reposiciones',
            'accion'
        ]);

        $this->accion = $accion;

        $usuario = auth()->user();
        $personal = $usuario->personal;
        $rol = $usuario->rol_id;
        $sucursalId = $personal->trabajos()->latest()->first()?->sucursal_id;

        if ($accion === 'create') {
            $this->codigo = $this->generarCodigo('AD');
        }

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->reposiciones = Reposicion::with('existencia.existenciable')
            ->where('estado_revision', true)
            ->whereHas('existencia', function ($q) use ($rol, $sucursalId) {
                $q->where('existenciable_type', Etiqueta::class);
                if ($rol === 4 && $sucursalId) {
                    $q->where('sucursal_id', $sucursalId);
                }
            })
            ->when($this->accion === 'edit', function ($q) {
                $idsSeleccionados = array_keys($this->reposicionesSeleccionadas ?? []);
                $q->where(function ($sub) use ($idsSeleccionados) {
                    $sub->where('cantidad', '>', 0)
                        ->orWhereIn('id', $idsSeleccionados);
                });
            })
            ->when($this->accion === 'create', function ($q) {
                $q->where('cantidad', '>', 0);
            })
            ->get();

        $this->modal = true;
    }

    protected function generarCodigo($prefijo)
    {
        $fechaHoy = now()->format('Ymd');
        $ultimoCodigo = Adornado::whereDate('created_at', now()->toDateString())->latest('id')->value('codigo');
        $contador = 1;
        if ($ultimoCodigo) {
            $partes = explode('-', $ultimoCodigo);
            $contador = intval(end($partes)) + 1;
        }
        return $prefijo . '-' . $fechaHoy . '-' . str_pad($contador, 3, '0', STR_PAD_LEFT);
    }

    public function editar($id)
    {
        $adornado = Adornado::with('reposiciones')->findOrFail($id);
        $this->adornado_id = $adornado->id;
        $this->pedido_id = $adornado->pedido_id;
        $this->codigo = $adornado->codigo;
        $this->observaciones = $adornado->observaciones;

        $this->reposicionesSeleccionadas = $adornado->reposiciones
            ->mapWithKeys(fn($r) => [
                $r->id => [
                    'cantidad_usada' => $r->pivot->cantidad_usada ?? 0,
                    'merma' => $r->pivot->merma ?? 0,
                    'cantidad_disponible' => $r->cantidad + ($r->pivot->cantidad_usada ?? 0)
                ]
            ])->toArray();
    }

    public function toggleReposicion($reposicionId)
    {
        if (isset($this->reposicionesSeleccionadas[$reposicionId])) {
            unset($this->reposicionesSeleccionadas[$reposicionId]);
        } else {
            $this->reposicionesSeleccionadas[$reposicionId] = ['cantidad_usada' => 0, 'merma' => 0];
        }
    }

    public function actualizarCantidad($reposicionId, $campo, $valor)
    {
        if (isset($this->reposicionesSeleccionadas[$reposicionId])) {
            $this->reposicionesSeleccionadas[$reposicionId][$campo] = max(0, (float) $valor);
        }
    }

    public function guardar()
    {
        $this->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'codigo' => 'required|string|max:50|unique:adornados,codigo,' . $this->adornado_id,
        ]);

        if ($this->accion === 'create' && Adornado::where('pedido_id', $this->pedido_id)->exists()) {
            $this->addError('pedido_id', 'Este pedido ya tiene un adornado asociado.');
            return;
        }

        if (empty($this->reposicionesSeleccionadas) || collect($this->reposicionesSeleccionadas)->sum('cantidad_usada') <= 0) {
            $this->addError('reposicionesSeleccionadas', 'Debe seleccionar al menos una reposición con cantidad mayor a 0.');
            return;
        }

        DB::transaction(function () {
            if ($this->accion === 'create') {
                $adornado = Adornado::create([
                    'codigo' => $this->codigo,
                    'pedido_id' => $this->pedido_id,
                    'observaciones' => $this->observaciones,
                    'personal_id' => auth()->user()->personal->id ?? null,
                ]);
            } else {
                $adornado = Adornado::with('reposiciones')->findOrFail($this->adornado_id);
                foreach ($adornado->reposiciones as $r) {
                    $r->cantidad += ($r->pivot->cantidad_usada ?? 0) + ($r->pivot->merma ?? 0);
                    $r->save();
                }
                $adornado->update([
                    'pedido_id' => $this->pedido_id,
                    'codigo' => $this->codigo,
                    'observaciones' => $this->observaciones,
                ]);
            }

            $syncData = [];
            foreach ($this->reposicionesSeleccionadas as $id => $data) {
                $cantidad = $data['cantidad_usada'] ?? 0;
                $merma = $data['merma'] ?? 0;
                $reposicion = Reposicion::find($id);
                if (!$reposicion)
                    continue;

                if (($cantidad + $merma) > $reposicion->cantidad) {
                    $nombre = optional(optional($reposicion->existencia)->existenciable)->nombre ?? "ID {$reposicion->id}";
                    session()->flash('error', "La reposición '{$nombre}' no tiene suficiente cantidad disponible.");
                    $this->dispatch('alertaError');
                    return;
                }

                $reposicion->cantidad -= ($cantidad + $merma);
                $reposicion->save();

                if ($cantidad > 0 || $merma > 0) {
                    $syncData[$id] = ['cantidad_usada' => $cantidad, 'merma' => $merma];
                }
            }

            $adornado->reposiciones()->sync($syncData);
        });

        session()->flash('mensaje', 'Adornado guardado correctamente.');
        $this->cerrarModal();
    }

    public function eliminar($id)
    {
        $adornado = Adornado::with('reposiciones')->findOrFail($id);

        DB::transaction(function () use ($adornado) {
            foreach ($adornado->reposiciones as $reposicion) {
                $cantidadRestaurar = ($reposicion->pivot->cantidad_usada ?? 0) + ($reposicion->pivot->merma ?? 0);
                if ($cantidadRestaurar > 0) {
                    $reposicion->cantidad += $cantidadRestaurar;
                    $reposicion->save();
                }
            }

            $adornado->reposiciones()->detach();
            $adornado->delete();
        });

        session()->flash('mensaje', 'Adornado eliminado correctamente.');
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->modalDetalle = false;
        $this->reset([
            'adornado_id',
            'pedido_id',
            'codigo',
            'observaciones',
            'reposicionesSeleccionadas',
            'reposiciones',
            'accion'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
