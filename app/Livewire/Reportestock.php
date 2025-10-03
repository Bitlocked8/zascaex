<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignado;
use App\Models\Reposicion;
use App\Models\Existencia;

class Reportestock extends Component
{
    public $asignados;
    public $reposicions;
    public $tablaActiva = null;

    public $fechaInicio;
    public $fechaFin;
    public $existenciableId;

    public $ocultarMontos = false;
    public $ocultarCantidades = false;

    public $existenciables; // lista de existencias con reposiciones

    public function mount()
    {
        // Inicializar reposiciones y asignados sin filtros
        $this->asignados = Asignado::with(['reposiciones.comprobantes', 'personal'])->get();
        $this->reposicions = Reposicion::with('comprobantes', 'personal', 'proveedor', 'asignados', 'existencia')->get();

        // Existencias que ya tienen reposiciones
        $this->existenciables = Existencia::whereIn(
            'id',
            Reposicion::pluck('existencia_id')->unique()
        )->get();
    }

    public function mostrarTabla($tabla)
    {
        $this->tablaActiva = $tabla;
    }

    public function aplicarFiltros()
    {
        // Filtrar asignados
        $this->asignados = Asignado::with(['reposiciones.comprobantes', 'personal'])
            ->when($this->fechaInicio, function ($q) {
                $q->whereDate('fecha', '>=', $this->fechaInicio);
            })
            ->when($this->fechaFin, function ($q) {
                $q->whereDate('fecha', '<=', $this->fechaFin);
            })
            ->when($this->existenciableId, function ($q) {
                $q->whereHas('reposiciones.existencia', function ($qr) {
                    $qr->where('id', $this->existenciableId);
                });
            })
            ->get();

        // Filtrar reposiciones
        $this->reposicions = Reposicion::with('comprobantes', 'personal', 'proveedor', 'asignados', 'existencia')
            ->when($this->fechaInicio, function ($q) {
                $q->whereDate('fecha', '>=', $this->fechaInicio);
            })
            ->when($this->fechaFin, function ($q) {
                $q->whereDate('fecha', '<=', $this->fechaFin);
            })
            ->when($this->existenciableId, function ($q) {
                $q->where('existencia_id', $this->existenciableId);
            })
            ->get();
    }

    public function limpiarFiltros()
    {
        $this->fechaInicio = null;
        $this->fechaFin = null;
        $this->existenciableId = null;

        $this->mount(); // recargar sin filtros
    }

    public function render()
    {
        return view('livewire.reportestock', [
            'asignados' => $this->asignados,
            'reposicions' => $this->reposicions,
            'tablaActiva' => $this->tablaActiva,
            'existenciables' => $this->existenciables,
        ]);
    }

    public function deseleccionarTabla()
    {
        $this->tablaActiva = null;
    }

    public function toggleCantidades()
    {
        $this->ocultarCantidades = !$this->ocultarCantidades;
    }

    public function toggleMontos()
    {
        $this->ocultarMontos = !$this->ocultarMontos;
    }
}
