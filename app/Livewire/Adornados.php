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
    public $search = '';
    public $modal = false;
    public $modalDetalle = false;

    public $adornado_id = null;
    public $pedido_id;
    public $codigo;
    public $observaciones = '';
    public $reposicionesSeleccionadas = [];
    public $accion = 'create';

    protected $rules = [
        'pedido_id' => 'required|exists:pedidos,id',
        'codigo' => 'required|string|max:50|unique:adornados,codigo',
        'observaciones' => 'nullable|string|max:500',
        'reposicionesSeleccionadas' => 'array',
    ];

    public function render()
    {
        $adornados = Adornado::with(['pedido', 'reposiciones.existencia.existenciable'])
            ->when($this->search, fn($q) => $q->where('codigo', 'like', "%{$this->search}%")
                ->orWhereHas('pedido', fn($q2) => $q2->where('codigo', 'like', "%{$this->search}%")))
            ->latest()->get();

        $pedidosQuery = Pedido::query();
        if ($this->accion === 'edit' && $this->adornado_id) {
            $pedidosQuery->where(function ($q) {
                $q->whereDoesntHave('adornados')
                  ->orWhere('id', $this->pedido_id);
            });
        } else {
            $pedidosQuery->whereDoesntHave('adornados');
        }
        $pedidos = $pedidosQuery->get();

        $reposicionesQuery = Reposicion::with('existencia.existenciable')
            ->where('estado_revision', true)
            ->whereHas('existencia', fn($q) => $q->where('existenciable_type', Etiqueta::class));

        if ($this->accion === 'edit') {
            $reposicionIdsAsignadas = array_keys($this->reposicionesSeleccionadas);
            $reposicionesQuery->where(function ($q) use ($reposicionIdsAsignadas) {
                $q->where('cantidad', '>', 0)
                  ->orWhereIn('id', $reposicionIdsAsignadas);
            });
        } else {
            $reposicionesQuery->where('cantidad', '>', 0);
        }

        $reposiciones = $reposicionesQuery->get();

        return view('livewire.adornados', compact('adornados', 'pedidos', 'reposiciones'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['adornado_id','pedido_id','codigo','observaciones','reposicionesSeleccionadas','accion']);
        $this->accion = $accion;
        if ($accion === 'create') $this->codigo = $this->generarCodigo('AD');
        if ($accion === 'edit' && $id) $this->editar($id);
        $this->modal = true;
    }

    protected function generarCodigo($prefijo)
    {
        $fechaHoy = now()->format('Ymd');
        $ultimoCodigo = Adornado::whereDate('created_at', now()->toDateString())
            ->latest('id')->value('codigo');
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
                    'cantidad_disponible' => $r->cantidad + ($r->pivot->cantidad_usada ?? 0) + ($r->pivot->merma ?? 0)
                ]
            ])->toArray();
    }

    public function toggleReposicion($reposicionId)
    {
        if (isset($this->reposicionesSeleccionadas[$reposicionId])) {
            unset($this->reposicionesSeleccionadas[$reposicionId]);
        } else {
            $this->reposicionesSeleccionadas[$reposicionId] = ['cantidad_usada'=>0,'merma'=>0];
        }
    }

    public function actualizarCantidad($reposicionId, $campo, $valor)
    {
        if (isset($this->reposicionesSeleccionadas[$reposicionId])) {
            $this->reposicionesSeleccionadas[$reposicionId][$campo] = max(0, (float)$valor);
        }
    }

    public function guardar()
    {
        $this->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'codigo' => 'required|string|max:50|unique:adornados,codigo,' . $this->adornado_id,
        ]);

        if ($this->accion === 'create' && Adornado::where('pedido_id', $this->pedido_id)->exists()) {
            $this->addError('pedido_id','Este pedido ya tiene un adornado asociado.');
            return;
        }

        DB::transaction(function () {
            if ($this->accion === 'create') {
                $adornado = Adornado::create([
                    'codigo'=>$this->codigo,
                    'pedido_id'=>$this->pedido_id,
                    'observaciones'=>$this->observaciones
                ]);
            } else {
                $adornado = Adornado::with('reposiciones')->findOrFail($this->adornado_id);
                foreach ($adornado->reposiciones as $r) {
                    $r->cantidad += ($r->pivot->cantidad_usada ?? 0) + ($r->pivot->merma ?? 0);
                    $r->save();
                }
                $adornado->update([
                    'pedido_id'=>$this->pedido_id,
                    'codigo'=>$this->codigo,
                    'observaciones'=>$this->observaciones
                ]);
            }

            $syncData = [];
            foreach ($this->reposicionesSeleccionadas as $id => $data) {
                $cantidad = $data['cantidad_usada'] ?? 0;
                $merma = $data['merma'] ?? 0;
                $reposicion = Reposicion::find($id);
                if (!$reposicion) continue;
                $reposicion->cantidad -= ($cantidad);
                if ($reposicion->cantidad < 0) $reposicion->cantidad = 0;
                $reposicion->save();
                if ($cantidad > 0 || $merma > 0) {
                    $syncData[$id] = ['cantidad_usada'=>$cantidad,'merma'=>$merma];
                }
            }

            $adornado->reposiciones()->sync($syncData);
        });

        session()->flash('mensaje','Adornado guardado correctamente.');
        $this->cerrarModal();
    }

    public function eliminar($id)
    {
        $adornado = Adornado::with('reposiciones')->findOrFail($id);
        DB::transaction(function () use ($adornado) {
            foreach ($adornado->reposiciones as $r) {
                $r->cantidad += ($r->pivot->cantidad_usada ?? 0) + ($r->pivot->merma ?? 0);
                $r->save();
            }
            $adornado->reposiciones()->detach();
            $adornado->delete();
        });
        session()->flash('mensaje','Adornado eliminado correctamente.');
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->modalDetalle = false;
        $this->reset(['adornado_id','pedido_id','codigo','observaciones','reposicionesSeleccionadas','accion']);
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
