<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Reposicion;
use App\Models\Asignado;

class Reportestock extends Component
{
    public $search = '';
    public $fechaInicio = '';
    public $fechaFinal = '';
    public $sucursalId = '';
    public $mostrarReporte = false;

    public function mount()
    {
        $this->fechaInicio = now()->startOfYear()->format('Y-m-d');
        $this->fechaFinal = now()->endOfYear()->format('Y-m-d');
    }

    public function limpiarFiltros()
    {
        $this->search = '';
        $this->fechaInicio = now()->startOfYear()->format('Y-m-d');
        $this->fechaFinal = now()->endOfYear()->format('Y-m-d');
    }

    public function render()
    {
        // Reposiciones
        $reposiciones = Reposicion::with([
            'existencia.existenciable',
            'personal',
            'proveedor',
            'comprobantes'
        ])
            ->when($this->fechaInicio && $this->fechaFinal, function ($query) {
                $query->whereBetween('fecha', [$this->fechaInicio, $this->fechaFinal]);
            })
            ->when($this->search, function ($query) {
                $query->where('codigo', 'like', '%' . $this->search . '%');
            })
            ->orderBy('fecha', 'desc')
            ->get();

        // Asignados
        $asignados = Asignado::with(['existencia.existenciable', 'personal'])
            ->when($this->fechaInicio && $this->fechaFinal, function ($query) {
                $query->whereBetween('fecha', [$this->fechaInicio, $this->fechaFinal]);
            })
            ->when($this->search, function ($query) {
                $query->where('codigo', 'like', '%' . $this->search . '%');
            })
            ->orderBy('fecha', 'desc')
            ->get();

        return view('livewire.reportestock', [
            'reposiciones' => $reposiciones,
            'asignados'    => $asignados,
        ]);
    }
}
