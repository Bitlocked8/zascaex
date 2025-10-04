<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Traspaso as TraspasoModel;
use App\Models\Reposicion;
use App\Models\Personal;
use App\Models\ComprobantePago;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class Traspaso extends Component
{
    public $modal = false;
    public $modalError = false;
    public $mensajeError = '';
    public $accion = 'create';
    public $codigo;
    public $fecha_traspaso;

    public $traspaso_id;
    public $origen_id;
    public $destino_id;
    public $estado;
    public $cantidad;
    public $personal_id;
    public $observaciones;
    public $search = '';
    public $traspasos;
    public $reposicionesOrigen;
    public $reposicionesDestino;
    public $personals;

    public function mount()
    {
        $usuario = auth()->user();

        // Personal
        if ($usuario->rol_id == 1) {
            $this->personals = Personal::all();
        } else {
            $this->personals = collect([$usuario->personal]); // solo logueado
            $this->personal_id = $usuario->personal->id; // asignado automáticamente
        }

        // Reposiciones
        if ($usuario->rol_id == 1) {
            $this->reposicionesOrigen = Reposicion::with('existencia', 'comprobantes')->get();
            $this->reposicionesDestino = Reposicion::with('existencia', 'comprobantes')->get();
        } else {
            $trabajo = $usuario->personal->trabajos()->where('estado', 1)->first();
            $sucursalId = $trabajo ? $trabajo->sucursal_id : null;

            $this->reposicionesOrigen = Reposicion::with('existencia', 'comprobantes')
                ->whereHas('existencia', fn($q) => $q->where('sucursal_id', $sucursalId))
                ->get();

            $this->reposicionesDestino = Reposicion::with('existencia', 'comprobantes')
                ->whereHas('existencia', fn($q) => $q->where('sucursal_id', '!=', $sucursalId))
                ->get();
        }

        $this->traspasos = collect();
    }



    public function abrirModal($accion, $id = null)
    {
        $this->accion = $accion;
        $this->modal = true;
        $this->reposicionesOrigen = Reposicion::with('existencia', 'comprobantes')->get();
        $this->reposicionesDestino = Reposicion::with('existencia', 'comprobantes')->get();
        $this->personals = Personal::all();

        if ($accion === 'create') {
            $this->codigo = 'T-' . now()->format('Ymd-His');
            $this->fecha_traspaso = now()->toDateTimeString();
            $this->cantidad = null;
            $this->origen_id = null;
            $this->destino_id = null;
            $this->personal_id = null;
            $this->observaciones = null;
            $this->estado = null;
        }

        if ($accion === 'edit' && $id) {
            $traspaso = TraspasoModel::findOrFail($id);
            $this->traspaso_id = $traspaso->id;
            $this->codigo = $traspaso->codigo;
            $this->fecha_traspaso = $traspaso->fecha_traspaso;
            $this->origen_id = $traspaso->reposicion_origen_id;
            $this->destino_id = $traspaso->reposicion_destino_id;
            $this->cantidad = $traspaso->cantidad;
            $this->observaciones = $traspaso->observaciones;
            $this->personal_id = $traspaso->personal_id;
            $this->estado = $traspaso->estado;
        }
    }

    public function guardar()
    {
        $usuario = auth()->user();
        $personal = $usuario->personal;

        if (!$personal) {
            $this->mensajeError = "No estás asignado a un personal válido.";
            $this->modalError = true;
            return;
        }

        if ($this->accion === 'create') {
            $validator = Validator::make([
                'origen_id' => $this->origen_id,
                'destino_id' => $this->destino_id,
                'cantidad' => $this->cantidad,
            ], [
                'origen_id' => 'required|different:destino_id|exists:reposicions,id',
                'destino_id' => 'required|exists:reposicions,id',
                'cantidad' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                $this->mensajeError = implode("\n", $validator->errors()->all());
                $this->modalError = true;
                return;
            }

            $origen = Reposicion::findOrFail($this->origen_id);
            $destino = Reposicion::findOrFail($this->destino_id);

            if ($this->cantidad > $origen->cantidad) {
                $this->mensajeError = "La cantidad solicitada excede la disponible en el origen ({$origen->cantidad}).";
                $this->modalError = true;
                return;
            }

            DB::transaction(function () use ($origen, $destino, $personal) {
                $cantidadOriginal = $origen->cantidad;
                $origen->cantidad -= $this->cantidad;
                $origen->cantidad_inicial -= $this->cantidad;
                $origen->save();
                $destino->cantidad += $this->cantidad;
                $destino->cantidad_inicial += $this->cantidad;
                $destino->save();
                $mensaje = "Se movieron {$this->cantidad} unidades. Origen: {$origen->cantidad_inicial} inicial, {$origen->cantidad} disponibles. Destino: {$destino->cantidad_inicial} inicial, {$destino->cantidad} disponibles.";
                TraspasoModel::create([
                    'codigo' => 'T-' . now()->format('YmdHis'),
                    'reposicion_origen_id' => $origen->id,
                    'reposicion_destino_id' => $destino->id,
                    'personal_id' => $personal->id,
                    'cantidad' => $this->cantidad,
                    'fecha_traspaso' => now(),
                    'observaciones' => $mensaje,
                    'estado' => $this->estado,
                ]);

                // Ajustar comprobantes
                $comprobanteOrigen = $origen->comprobantes->first();
                if ($comprobanteOrigen) {
                    // Costo unitario basado en cantidad antes del traspaso
                    $costoUnitario = $comprobanteOrigen->monto / $cantidadOriginal;

                    // Monto del traspaso
                    $montoTraspaso = $this->cantidad * $costoUnitario;

                    // Restar del comprobante origen
                    $comprobanteOrigen->monto -= $montoTraspaso;
                    $comprobanteOrigen->save();

                    // Crear comprobante en destino
                    ComprobantePago::create([
                        'reposicion_id' => $destino->id,
                        'codigo' => 'PAGO-' . now()->format('YmdHis'),
                        'fecha' => now()->format('Y-m-d'),
                        'monto' => $montoTraspaso,
                        'observaciones' => ($comprobanteOrigen->observaciones ?? 'N/A') . ' | Generado automáticamente por traspaso',
                    ]);
                }
            });
        } elseif ($this->accion === 'edit' && $this->traspaso_id) {
            $traspaso = TraspasoModel::findOrFail($this->traspaso_id);
            $traspaso->update([
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
            ]);
        }

        $this->cerrarModal();
        session()->flash('message', 'Traspaso guardado correctamente!');
    }

    public function eliminarTraspaso($id)
    {
        $traspaso = TraspasoModel::findOrFail($id);

        DB::transaction(function () use ($traspaso) {
            $origen = Reposicion::find($traspaso->reposicion_origen_id);
            $destino = Reposicion::find($traspaso->reposicion_destino_id);

            if ($origen && $destino) {
                $origen->cantidad += $traspaso->cantidad;
                $origen->cantidad_inicial += $traspaso->cantidad;
                $origen->save();
                $destino->cantidad -= $traspaso->cantidad;
                $destino->cantidad_inicial -= $traspaso->cantidad;
                $destino->save();
                $comprobanteOrigen = $origen->comprobantes->first();
                $comprobanteDestino = $destino->comprobantes()
                    ->where('observaciones', 'like', '%Generado automáticamente por traspaso%')
                    ->first();
                if ($comprobanteOrigen && $comprobanteDestino) {
                    $comprobanteOrigen->monto += $comprobanteDestino->monto;
                    $comprobanteOrigen->save();
                    $comprobanteDestino->delete();
                }
            }

            $traspaso->delete();
        });

        $this->traspasos = $this->traspasos->filter(fn($t) => $t->id != $id);

        session()->flash('message', 'Traspaso y comprobantes asociados eliminados correctamente!');
    }


    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'traspaso_id',
            'origen_id',
            'destino_id',
            'cantidad',
            'observaciones',
            'estado',
        ]);
    }

    public function render()
    {
        $usuario = auth()->user();
        $rol = $usuario->rol_id;

        // Sucursal del empleado
        $sucursalId = null;
        if ($rol == 2) {
            $trabajo = $usuario->personal->trabajos()->where('estado', 1)->first();
            $sucursalId = $trabajo ? $trabajo->sucursal_id : null;
        }

        // Reposiciones para origen y destino
        if ($rol == 1) {
            // Admin: todos los items
            $this->reposicionesOrigen = Reposicion::with('existencia', 'comprobantes')->get();
            $this->reposicionesDestino = Reposicion::with('existencia', 'comprobantes')->get();
        } else {
            // Empleado: origen de su sucursal, destino fuera de su sucursal
            $this->reposicionesOrigen = Reposicion::with('existencia', 'comprobantes')
                ->whereHas('existencia', fn($q) => $q->where('sucursal_id', $sucursalId))
                ->get();

            $this->reposicionesDestino = Reposicion::with('existencia', 'comprobantes')
                ->whereHas('existencia', fn($q) => $q->where('sucursal_id', '!=', $sucursalId))
                ->get();
        }

        // Traspasos
        $query = TraspasoModel::with([
            'personal',
            'reposicionOrigen.existencia',
            'reposicionOrigen.comprobantes',
            'reposicionDestino.existencia',
            'reposicionDestino.comprobantes',
        ]);

        // Filtro según rol
        if ($rol == 2 && $sucursalId) {
            // Solo los traspasos creados por el empleado
            $query->where('personal_id', $usuario->personal->id);
        }

        // Filtro de búsqueda
        if ($this->search) {
            $query->where(
                fn($q) =>
                $q->where('codigo', 'like', "%{$this->search}%")
                    ->orWhere('observaciones', 'like', "%{$this->search}%")
            );
        }

        $this->traspasos = $query->latest()->get();
        $this->personals = $rol == 1 ? Personal::all() : collect([$usuario->personal]);

        return view('livewire.traspaso');
    }
}
