<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Gasto;

class Gastos extends Component
{
    use WithFileUploads;

    public $gastos;
    public $descripcion;
    public $monto;
    public $archivo_evidencia;
    public $gastoSeleccionado = null;
    public $modalVisible = false;
    public $search = '';
    public $soloHoy = true;
    public function mount()
    {
        $this->cargarGastos();
    }

    public function cargarGastos()
    {
        $query = Gasto::query()->orderBy('fecha', 'desc');

        if (auth()->user()->rol_id !== 1) {
            $query->where('personal_id', auth()->user()->personal->id);
        }

        if ($this->soloHoy) {
            $query->whereDate('fecha', now()->toDateString());
        }

        if ($this->search) {
            $query->where('descripcion', 'like', "%{$this->search}%");
        }

        $this->gastos = $query->get();
    }

    public function abrirModal($gastoId = null)
    {
        if ($gastoId) {
            $gasto = Gasto::findOrFail($gastoId);
            if ($gasto->personal_id !== auth()->user()->personal->id && auth()->user()->rol_id !== 1) {
                return;
            }

            $this->gastoSeleccionado = $gasto;
            $this->descripcion = $gasto->descripcion;
            $this->monto = $gasto->monto;
            $this->archivo_evidencia = null;
        } else {
            $this->gastoSeleccionado = null;
            $this->reset(['descripcion', 'monto', 'archivo_evidencia']);
        }

        $this->modalVisible = true;
    }

    public function cerrarModal()
    {
        $this->modalVisible = false;
        $this->gastoSeleccionado = null;
        $this->reset(['descripcion', 'monto', 'archivo_evidencia']);
    }

    public function guardarGasto()
    {
        $this->validate([
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'archivo_evidencia' => 'nullable|file|max:10240',
        ]);

        $rutaArchivo = $this->archivo_evidencia
            ? $this->archivo_evidencia->store('gastos', 'public')
            : ($this->gastoSeleccionado->archivo_evidencia ?? null);

        if ($this->gastoSeleccionado) {
            if ($this->gastoSeleccionado->personal_id !== auth()->user()->personal->id && auth()->user()->rol_id !== 1) {
                return;
            }

            $this->gastoSeleccionado->update([
                'descripcion' => $this->descripcion,
                'monto' => $this->monto,
                'archivo_evidencia' => $rutaArchivo,
            ]);
        } else {
            Gasto::create([
                'descripcion' => $this->descripcion,
                'monto' => $this->monto,
                'fecha' => now(),
                'archivo_evidencia' => $rutaArchivo,
                'personal_id' => auth()->user()->personal->id,
            ]);
        }

        $this->cerrarModal();
        $this->cargarGastos();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'soloHoy'])) {
            $this->cargarGastos();
        }
    }

    public function eliminarGasto($gastoId)
    {
        $gasto = Gasto::findOrFail($gastoId);
        if ($gasto->personal_id !== auth()->user()->personal->id && auth()->user()->rol_id !== 1) {
            return;
        }

        $gasto->delete();
        $this->cargarGastos();
    }

    public function render()
    {
        $total = $this->gastos->sum('monto');
        return view('livewire.gastos', compact('total'));
    }
}
