<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Elaboracion;
use App\Models\Embotellado;
use App\Models\Etiquetado;
use App\Models\Sucursal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Reportestock extends Component
{
    use WithPagination;

    public $search = '';
    public $fechaInicio = '';
    public $fechaFinal = '';
    public $sucursalId = '';
    public $generarReporte = false;
    public $elaboraciones = [];
    public $embotellados = [];
    public $etiquetados = [];
    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->fechaInicio = Carbon::now()->startOfYear()->format('Y-m-d');
        $this->fechaFinal = Carbon::now()->endOfYear()->format('Y-m-d');
    }

    public function generarReporte()
    {
        $this->generarReporte = true;

        // // Depuración: Verificar filtros
        // Log::info('ReporteStock - Filtros:', [
        //     'sucursal_id' => $this->sucursalId,
        //     'fecha_inicio' => $this->fechaInicio,
        //     'fecha_final' => $this->fechaFinal
        // ]);

        // // Obtener elaboraciones (soplado) con relaciones
        // $this->elaboraciones = Elaboracion::with([
        //     'existenciaEntrada.existenciable',
        //     'existenciaSalida.existenciable'
        // ])
        //     ->where('sucursal_id', $this->sucursalId)
        //     ->whereBetween('fecha_elaboracion', [$this->fechaInicio, $this->fechaFinal])
        //     ->get()
        //     ->toArray();

        // // Depuración: Registrar elaboraciones
        // Log::info('ReporteStock - Elaboraciones:', [
        //     'count' => count($this->elaboraciones),
        //     'data' => $this->elaboraciones
        // ]);

        // // Obtener embotellados con relaciones
        // $this->embotellados = Embotellado::with([
        //     'existencia_base.existenciable',
        //     'existencia_tapa.existenciable',
        //     'existencia_producto.existenciable'
        // ])
        //     ->where('sucursal_id', $this->sucursalId)
        //     ->whereBetween('fecha_embotellado', [$this->fechaInicio, $this->fechaFinal])
        //     ->get()
        //     ->toArray();

        // // Depuración: Registrar embotellados
        // Log::info('ReporteStock - Embotellados:', [
        //     'count' => count($this->embotellados),
        //     'data' => $this->embotellados
        // ]);

        // // Obtener etiquetados con relaciones
        // $this->etiquetados = Etiquetado::with([
        //     'existencia_producto.existenciable',
        //     'existencia_etiqueta.existenciable',
        //     'existencia_stock.existenciable'
        // ])
        //     ->where('sucursal_id', $this->sucursalId)
        //     ->whereBetween('fecha_etiquetado', [$this->fechaInicio, $this->fechaFinal])
        //     ->get()
        //     ->toArray();

        // // Depuración: Registrar etiquetados
        // Log::info('ReporteStock - Etiquetados:', [
        //     'count' => count($this->etiquetados),
        //     'data' => $this->etiquetados
        // ]);

        $this->resetPage();
    }

    public function render()
    {
        $sucursales = Sucursal::all();
        $data = [
            'elaboraciones' => $this->elaboraciones,
            'embotellados' => $this->embotellados,
            'etiquetados' => $this->etiquetados,
            'sucursales' => $sucursales,
        ];

        // Depuración: Registrar datos pasados a la vista
        Log::info('ReporteStock - Datos para la vista:', [
            'elaboraciones_count' => count($data['elaboraciones']),
            'embotellados_count' => count($data['embotellados']),
            'etiquetados_count' => count($data['etiquetados'])
        ]);

        return view('livewire.reportestock', $data);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}