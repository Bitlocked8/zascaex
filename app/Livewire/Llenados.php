<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Llenado;
use App\Models\Asignado;
use App\Models\ComprobantePago;
use App\Models\Reposicion;
use App\Models\Existencia;
use Illuminate\Support\Facades\DB;

class Llenados extends Component
{
    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $llenado_id = null;

    public $asignado_base_id;
    public $asignado_tapa_id;
    public $existencia_destino_id;
    public $cantidad;
    public $merma_base = 0;
    public $merma_tapa = 0;
    public $estado = 0;
    public $observaciones = '';
    public $fecha;
    public $codigo;
    public $accion = 'create';
    public $confirmingDeleteLlenadoId = null;
    public $llenadoSeleccionado = null;

    protected $rules = [
        'asignado_base_id' => 'required|exists:asignados,id',
        'asignado_tapa_id' => 'required|exists:asignados,id',
        'existencia_destino_id' => 'required|exists:existencias,id',
        'cantidad' => 'nullable|numeric|min:0',
        'estado' => 'required|in:0,1,2',
        'observaciones' => 'nullable|string|max:500',
    ];

    public function render()
    {
        $llenados = Llenado::with(['asignadoBase', 'asignadoTapa', 'existencia', 'reposicion'])
            ->when(
                $this->search,
                fn($q) =>
                $q->where('codigo', 'like', "%{$this->search}%")
                    ->orWhereHas('existencia.existenciable', fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            )->get();

        // Asignaciones base (botellas)
        $asignacionesBase = Asignado::with('existencia.existenciable')
            ->whereHas('existencia', fn($q) => $q->where('existenciable_type', \App\Models\Base::class))
            ->where('cantidad', '>', 0)
            ->get();

        // Asignaciones tapa
        $asignacionesTapa = Asignado::with('existencia.existenciable')
            ->whereHas('existencia', fn($q) => $q->where('existenciable_type', \App\Models\Tapa::class))
            ->where('cantidad', '>', 0)
            ->get();

        // Existencias destino → Producto final
        $existenciasDestino = Existencia::with('existenciable')
            ->where('existenciable_type', \App\Models\Producto::class)
            ->get();

        // Personal
        $personales = \App\Models\Personal::orderBy('nombres')->get();

        return view('livewire.llenados', compact(
            'llenados',
            'asignacionesBase',
            'asignacionesTapa',
            'existenciasDestino',
            'personales' // ✅ corregido
        ));
    }


    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'llenado_id',
            'asignado_base_id',
            'asignado_tapa_id',
            'existencia_destino_id',
            'cantidad',
            'merma_base',
            'merma_tapa',
            'estado',
            'observaciones',
            'fecha',
            'codigo',
            'llenadoSeleccionado'
        ]);

        $this->accion = $accion;

        if ($accion === 'create') {
            $this->codigo = $this->generarCodigo('L');
            $this->fecha = now();
        }

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    protected function generarCodigo($prefijo)
    {
        $fechaHoy = now()->format('Ymd');
        $ultimo = Llenado::whereDate('created_at', now()->toDateString())->latest('id')->first();
        $contador = $ultimo ? intval(substr($ultimo->codigo, -3)) + 1 : 1;
        return $prefijo . '-' . $fechaHoy . '-' . str_pad($contador, 3, '0', STR_PAD_LEFT);
    }

    public function editar($id)
    {
        $llenado = Llenado::findOrFail($id);

        $this->llenado_id = $llenado->id;
        $this->asignado_base_id = $llenado->asignado_base_id;
        $this->asignado_tapa_id = $llenado->asignado_tapa_id;
        $this->existencia_destino_id = $llenado->existencia_id;
        $this->cantidad = $llenado->cantidad;
        $this->merma_base = $llenado->merma_base;
        $this->merma_tapa = $llenado->merma_tapa;
        $this->estado = $llenado->estado;
        $this->observaciones = $llenado->observaciones;
        $this->fecha = $llenado->fecha;
        $this->codigo = $llenado->codigo;
        $this->llenadoSeleccionado = $llenado;
    }

    public function guardar()
    {
        $this->validate();

        $usuario = auth()->user()->load('personal');
        if (!$usuario->personal) {
            $this->addError('personal_id', 'El usuario no tiene personal asignado.');
            return;
        }
        $personalId = $usuario->personal->id;

        $asignadoBase = Asignado::findOrFail($this->asignado_base_id);
        $asignadoTapa = Asignado::findOrFail($this->asignado_tapa_id);
        $existenciaDestino = Existencia::findOrFail($this->existencia_destino_id);

        $cantidad = $this->cantidad ?? 0;

        DB::transaction(function () use ($asignadoBase, $asignadoTapa, $existenciaDestino, $personalId, $cantidad) {

            $reposicionDestino = $this->crearReposicion($existenciaDestino, $personalId);

            $llenado = Llenado::create([
                'codigo' => $this->codigo ?? $this->generarCodigo('L'),
                'asignado_base_id' => $asignadoBase->id,
                'asignado_tapa_id' => $asignadoTapa->id,
                'existencia_id' => $existenciaDestino->id,
                'cantidad' => $cantidad,
                'merma_base' => $this->merma_base,
                'merma_tapa' => $this->merma_tapa,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
                'fecha' => now(),
                'personal_id' => $personalId,
                'reposicion_id' => $reposicionDestino->id,
            ]);

            if ($this->estado == 2 && $cantidad > 0) {
                $asignadoBase->cantidad -= ($cantidad + $this->merma_base);
                $asignadoTapa->cantidad -= ($cantidad + $this->merma_tapa);
                $existenciaDestino->cantidad += $cantidad;

                $asignadoBase->save();
                $asignadoTapa->save();
                $existenciaDestino->save();

                $reposicionDestino->update([
                    'cantidad' => $cantidad,
                    'cantidad_inicial' => $cantidad,
                    'estado_revision' => true,
                ]);

                $this->crearComprobante($reposicionDestino, $asignadoBase, $cantidad);
            }
        });

        $this->cerrarModal();
        session()->flash('mensaje', 'Llenado guardado correctamente.');
    }

    private function crearReposicion($existenciaDestino, $personalId)
    {
        $codigoReposicion = 'R-' . now()->format('Ymd') . '-' . str_pad(
            Reposicion::whereDate('created_at', now()->toDateString())->count() + 1,
            3,
            '0',
            STR_PAD_LEFT
        );

        return Reposicion::create([
            'fecha' => now(),
            'codigo' => $codigoReposicion,
            'cantidad' => 0,
            'cantidad_inicial' => 0,
            'existencia_id' => $existenciaDestino->id,
            'personal_id' => $personalId,
            'observaciones' => $this->observaciones ?? 'Reposición creada desde llenado',
            'estado_revision' => 0,
        ]);
    }

    private function crearComprobante($reposicion, $asignado, $cantidadUsada)
    {
        ComprobantePago::where('reposicion_id', $reposicion->id)->delete();

        $precioUnitario = $asignado->precio_unitario ?? 1;
        $monto = $precioUnitario * $cantidadUsada;

        $comprobante = ComprobantePago::create([
            'reposicion_id' => $reposicion->id,
            'codigo' => 'PAGO-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'monto' => $monto,
            'fecha' => now(),
            'observaciones' => 'Pago generado por llenado ' . ($this->codigo ?? ''),
        ]);

        $reposicion->update(['monto_usado' => $comprobante->monto]);
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset();
    }

    public function confirmarEliminarLlenado($id)
    {
        $this->confirmingDeleteLlenadoId = $id;
    }

    public function eliminarLlenadoConfirmado()
    {
        if (!$this->confirmingDeleteLlenadoId) return;

        $this->eliminar($this->confirmingDeleteLlenadoId);
        $this->confirmingDeleteLlenadoId = null;
    }

    public function eliminar($llenado_id)
    {
        $llenado = Llenado::find($llenado_id);
        if (!$llenado) {
            session()->flash('error', 'El llenado no existe.');
            return;
        }

        DB::transaction(function () use ($llenado) {
            $base = $llenado->asignadoBase;
            $tapa = $llenado->asignadoTapa;
            $producto = $llenado->existencia;

            if ($llenado->estado == 2) {
                $base->cantidad += ($llenado->cantidad + $llenado->merma_base);
                $tapa->cantidad += ($llenado->cantidad + $llenado->merma_tapa);
                $producto->cantidad -= $llenado->cantidad;
                $base->save();
                $tapa->save();
                $producto->save();
            }

            if ($llenado->reposicion_id) {
                $reposicion = Reposicion::find($llenado->reposicion_id);
                if ($reposicion) $reposicion->delete();
            }

            $llenado->delete();
        });

        session()->flash('mensaje', 'Llenado eliminado correctamente.');
    }
}
