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
    public $mostrarReporte = false;
    public $elaboraciones = [];
    public $embotellados = [];
    public $etiquetados = [];
    public $preformas = [];
    public $bases = [];
    public $tapas = [];
    public $etiquetas = [];
    public $productos = [];
    public $stocks = [];
    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        // $this->fechaInicio = Carbon::now()->startOfYear()->format('Y-m-d');
        // $this->fechaFinal = Carbon::now()->endOfYear()->format('Y-m-d');
        $this->fechaInicio = '2000-01-01';
        $this->fechaFinal = Carbon::now()->endOfYear()->format('Y-m-d');
    }

    public function generarReporte()
    {
        $this->mostrarReporte = true;

        // Depuración: Verificar filtros
        Log::info('ReporteStock - Filtros:', [
            'sucursal_id' => $this->sucursalId,
            'fecha_inicio' => $this->fechaInicio,
            'fecha_final' => $this->fechaFinal
        ]);

        // Obtener elaboraciones (soplado) con relaciones
        $this->elaboraciones = Elaboracion::with([
            'existenciaEntrada.existenciable',
            'existenciaSalida.existenciable'
        ])
            ->where('sucursal_id', $this->sucursalId)
            ->whereBetween('fecha_elaboracion', [$this->fechaInicio, $this->fechaFinal])
            ->get();

        // Depuración: Registrar elaboraciones
        Log::info('ReporteStock - Elaboraciones:', [
            'count' => $this->elaboraciones->count(),
            'data' => $this->elaboraciones->toArray()
        ]);

        // Obtener embotellados con relaciones
        $this->embotellados = Embotellado::with([
            'existenciaBase.existenciable',
            'existenciaTapa.existenciable',
            'existenciaProducto.existenciable'
        ])
            ->where('sucursal_id', $this->sucursalId)
            ->whereBetween('fecha_embotellado', [$this->fechaInicio, $this->fechaFinal])
            ->get();

        // Depuración: Registrar embotellados
        Log::info('ReporteStock - Embotellados:', [
            'count' => $this->embotellados->count(),
            'data' => $this->embotellados->toArray()
        ]);

        // Obtener etiquetados con relaciones
        $this->etiquetados = Etiquetado::with([
            'existenciaProducto.existenciable',
            'existenciaEtiqueta.existenciable',
            'existenciaStock.existenciable'
        ])
            ->where('sucursal_id', $this->sucursalId)
            ->whereBetween('fecha_etiquetado', [$this->fechaInicio, $this->fechaFinal])
            ->get();

        // Depuración: Registrar etiquetados
        Log::info('ReporteStock - Etiquetados:', [
            'count' => $this->etiquetados->count(),
            'data' => $this->etiquetados->toArray()
        ]);

        // Generar arrays para cada elemento
        // Preformas: usadas (cantidad_entrada) y mermas (merma) desde Elaboracion
        $this->preformas = $this->elaboraciones->map(function ($elaboracion) {
            return [
                'id' => $elaboracion->existenciaEntrada->existenciable->id ?? null,
                'insumo' => $elaboracion->existenciaEntrada->existenciable->insumo ?? 'N/A',
                'usadas' => $elaboracion->cantidad_entrada ?? 0,
                'mermas' => $elaboracion->merma ?? 0,
            ];
        })->toArray();

        // Bases: producidas (cantidad_salida) desde Elaboracion, usadas (cantidad_base_usada) y mermas (merma_base) desde Embotellado
        $this->bases = [];
        $basesMap = [];

        // Bases producidas desde Elaboracion
        foreach ($this->elaboraciones as $elaboracion) {
            $baseId = $elaboracion->existenciaSalida->existenciable->id ?? null;
            if ($baseId) {
                if (!isset($basesMap[$baseId])) {
                    $basesMap[$baseId] = [
                        'id' => $baseId,
                        'insumo' => $elaboracion->existenciaSalida->existenciable->insumo ?? 'N/A',
                        'producidas' => 0,
                        'usadas' => 0,
                        'mermas' => 0,
                    ];
                }
                $basesMap[$baseId]['producidas'] += $elaboracion->cantidad_salida ?? 0;
            }
        }

        // Bases usadas y mermas desde Embotellado
        foreach ($this->embotellados as $embotellado) {
            $baseId = $embotellado->existenciaBase->existenciable->id ?? null;
            if ($baseId) {
                if (!isset($basesMap[$baseId])) {
                    $basesMap[$baseId] = [
                        'id' => $baseId,
                        'insumo' => $embotellado->existenciaBase->existenciable->insumo ?? 'N/A',
                        'producidas' => 0,
                        'usadas' => 0,
                        'mermas' => 0,
                    ];
                }
                $basesMap[$baseId]['usadas'] += $embotellado->cantidad_base_usada ?? 0;
                $basesMap[$baseId]['mermas'] += $embotellado->merma_base ?? 0;
            }
        }
        $this->bases = array_values($basesMap);

        // Tapas: usadas (cantidad_tapa_usada) y mermas (merma_tapa) desde Embotellado
        $this->tapas = $this->embotellados->map(function ($embotellado) {
            return [
                'id' => $embotellado->existenciaTapa->existenciable->id ?? null,
                'insumo' => $embotellado->existenciaTapa->existenciable->insumo ?? 'N/A',
                'usadas' => $embotellado->cantidad_tapa_usada ?? 0,
                'mermas' => $embotellado->merma_tapa ?? 0,
            ];
        })->toArray();

        // Productos: producidos (cantidad_producida) desde Embotellado, usados (cantidad_producto_usado) y mermas (merma_producto) desde Etiquetado
        $this->productos = [];
        $productosMap = [];

        // Productos producidos desde Embotellado
        foreach ($this->embotellados as $embotellado) {
            $productoId = $embotellado->existenciaProducto->existenciable->id ?? null;
            if ($productoId) {
                if (!isset($productosMap[$productoId])) {
                    $productosMap[$productoId] = [
                        'id' => $productoId,
                        'insumo' => $embotellado->existenciaProducto->existenciable->insumo ?? 'N/A',
                        'producidos' => 0,
                        'usados' => 0,
                        'mermas' => 0,
                    ];
                }
                $productosMap[$productoId]['producidos'] += $embotellado->cantidad_producida ?? 0;
            }
        }

        // Productos usados y mermas desde Etiquetado
        foreach ($this->etiquetados as $etiquetado) {
            $productoId = $etiquetado->existenciaProducto->existenciable->id ?? null;
            if ($productoId) {
                if (!isset($productosMap[$productoId])) {
                    $productosMap[$productoId] = [
                        'id' => $productoId,
                        'insumo' => $etiquetado->existenciaProducto->existenciable->insumo ?? 'N/A',
                        'producidos' => 0,
                        'usados' => 0,
                        'mermas' => 0,
                    ];
                }
                $productosMap[$productoId]['usados'] += $etiquetado->cantidad_producto_usado ?? 0;
                $productosMap[$productoId]['mermas'] += $etiquetado->merma_producto ?? 0;
            }
        }
        $this->productos = array_values($productosMap);

        // Etiquetas: usadas (cantidad_etiqueta_usada) y mermas (merma_etiqueta) desde Etiquetado
        $this->etiquetas = $this->etiquetados->map(function ($etiquetado) {
            return [
                'id' => $etiquetado->existenciaEtiqueta->existenciable->id ?? null,
                'insumo' => $etiquetado->existenciaEtiqueta->existenciable->insumo ?? 'N/A',
                'usadas' => $etiquetado->cantidad_etiqueta_usada ?? 0,
                'mermas' => $etiquetado->merma_etiqueta ?? 0,
            ];
        })->toArray();

        // Stocks: producidos (cantidad_stock) desde Etiquetado
        $this->stocks = $this->etiquetados->map(function ($etiquetado) {
            return [
                'id' => $etiquetado->existenciaStock->existenciable->id ?? null,
                'insumo' => $etiquetado->existenciaStock->existenciable->insumo ?? 'N/A',
                'producidos' => $etiquetado->cantidad_stock ?? 0,
            ];
        })->toArray();

        // Depuración: Registrar arrays generados
        Log::info('ReporteStock - Arrays generados:', [
            'preformas_count' => count($this->preformas),
            'bases_count' => count($this->bases),
            'tapas_count' => count($this->tapas),
            'productos_count' => count($this->productos),
            'etiquetas_count' => count($this->etiquetas),
            'stocks_count' => count($this->stocks),
        ]);

        // $this->resetPage();
    }

    public function render()
    {
        $sucursales = Sucursal::all();
        $data = [
            'elaboraciones' => $this->elaboraciones,
            'embotellados' => $this->embotellados,
            'etiquetados' => $this->etiquetados,
            'preformas' => $this->preformas,
            'bases' => $this->bases,
            'tapas' => $this->tapas,
            'productos' => $this->productos,
            'etiquetas' => $this->etiquetas,
            'stocks' => $this->stocks,
            'sucursales' => $sucursales,
        ];

        // Depuración: Registrar datos pasados a la vista
        Log::info('ReporteStock - Datos para la vista:', [
            'elaboraciones_count' => count($data['elaboraciones']),
            'embotellados_count' => count($data['embotellados']),
            'etiquetados_count' => count($data['etiquetados']),
            'preformas_count' => count($data['preformas']),
            'bases_count' => count($data['bases']),
            'tapas_count' => count($data['tapas']),
            'productos_count' => count($data['productos']),
            'etiquetas_count' => count($data['etiquetas']),
            'stocks_count' => count($data['stocks']),
        ]);

        return view('livewire.reportestock', $data);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}