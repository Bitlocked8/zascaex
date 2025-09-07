<?php

namespace App\Livewire;

use App\Models\Sucursal;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\Personal;
use App\Models\Existencia;
use App\Models\ItemCompra;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\DB;

class Compras extends Component
{
    use WithPagination;

    public $search = '';
    public $modal = false;
    public $detalleModal = false;
    public $accion = 'create';
    public $compraId = null;

    public $fecha;
    public $observaciones;
    public $proveedor_id;
    public $personal_id;
    public $existencia_id;
    public $item_cantidad;
    public $item_precio;
    public $items = [];
    public $proveedors;
    public $personals;
    public $existenciasDisponibles = [];
    public $compraSeleccionada = null;
    public $sucursals;
    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'fecha' => 'required|date',
        'observaciones' => 'nullable|string|max:500',
        'proveedor_id' => 'required|exists:proveedors,id',
        'personal_id' => 'required|exists:personals,id',
        'existencia_id' => 'required|exists:existencias,id',
        'item_cantidad' => 'required|integer|min:1',
        'item_precio' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->proveedors = Proveedor::all();
        $this->personals = Personal::all();
        $this->sucursals = Sucursal::all();
    }

    public function render()
    {
        $compras = Compra::with(['proveedor', 'personal'])
            ->when($this->search, function ($query) {
                $query->where('observaciones', 'like', '%' . $this->search . '%');
            })
            ->orderBy('fecha', 'desc')
            ->paginate(5);

        return view('livewire.compras', compact('compras'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal($accion, $id = null)
    {
        $this->reset(['fecha', 'observaciones', 'proveedor_id', 'personal_id', 'compraId', 'items', 'existencia_id', 'item_cantidad', 'item_precio']);
        $this->accion = $accion;
        if ($accion === 'edit' && $id) {
            $this->editarCompra($id);
        } else {
            $this->fecha = now()->format('Y-m-d');
        }
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function editarCompra($id)
    {
        $compra = Compra::with(['proveedor', 'personal', 'itemCompras.existencia'])->findOrFail($id);

        $this->compraId = $compra->id;
        $this->fecha = $compra->fecha;
        $this->observaciones = $compra->observaciones;
        $this->proveedor_id = $compra->proveedor_id;
        $this->personal_id = $compra->personal_id;

        $this->items = $compra->itemCompras->map(function ($item) {
            return [
                'existencia' => $item->existencia,
                'cantidad' => $item->cantidad,
                'precio' => $item->precio,
            ];
        })->toArray();

        $this->cargarExistencias();
        $this->accion = 'edit';
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function verDetalle($id)
    {
        $this->compraSeleccionada = Compra::with(['proveedor', 'personal', 'itemCompras.existencia'])->findOrFail($id);
        $this->modal = false;
        $this->detalleModal = true;
    }

    public function cargarExistencias()
    {
        if ($this->proveedor_id) {
            $proveedor = Proveedor::find($this->proveedor_id);
            $tipo = $proveedor->tipo;

            $this->existenciasDisponibles = Existencia::when($tipo, function ($query) use ($tipo) {
                $modelos = [
                    'tapas' => \App\Models\Tapa::class,
                    'preformas' => \App\Models\Preforma::class,
                    'bases' => \App\Models\Base::class,
                    'etiquetas' => \App\Models\Etiqueta::class,
                ];

                if (isset($modelos[$tipo])) {
                    $query->where('existenciable_type', $modelos[$tipo]);
                }
            })
            ->with('existenciable')
            ->get();
        } else {
            $this->existenciasDisponibles = [];
        }
    }

    public function agregarItem()
    {
        $this->validate([
            'existencia_id' => 'required|exists:existencias,id',
            'item_cantidad' => 'required|integer|min:1',
            'item_precio' => 'required|numeric|min:0',
        ]);

        $existencia = Existencia::find($this->existencia_id);

        $this->items[] = [
            'existencia' => $existencia,
            'cantidad' => $this->item_cantidad,
            'precio' => $this->item_precio,
        ];

        $this->reset(['existencia_id', 'item_cantidad', 'item_precio']);
    }

    public function eliminarItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function guardarCompra()
    {
        $this->validate([
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string|max:500',
            'proveedor_id' => 'required|exists:proveedors,id',
            'personal_id' => 'required|exists:personals,id',
        ]);

        if (empty($this->items)) {
            LivewireAlert::title('Debe agregar al menos un ítem a la compra.')->error()->show();
            return;
        }

        try {
            // Usar una transacción para asegurar consistencia
            DB::transaction(function () {
                // Crear o actualizar la compra
                if ($this->accion === 'edit' && $this->compraId) {
                    $compra = Compra::findOrFail($this->compraId);
                    $compra->update([
                        'fecha' => $this->fecha,
                        'observaciones' => $this->observaciones,
                        'proveedor_id' => $this->proveedor_id,
                        'personal_id' => $this->personal_id,
                    ]);
                    // Eliminar ítems anteriores en modo edición
                    $compra->itemCompras()->delete();
                } else {
                    $compra = Compra::create([
                        'fecha' => $this->fecha,
                        'observaciones' => $this->observaciones,
                        'proveedor_id' => $this->proveedor_id,
                        'personal_id' => $this->personal_id,
                    ]);
                }

                // Procesar cada ítem y actualizar existencias
                foreach ($this->items as $item) {
                    $existencia = Existencia::find($item['existencia']->id);
                    if (!$existencia) {
                        throw new \Exception('Existencia no encontrada para el ítem con ID: ' . $item['existencia']->id);
                    }

                    // Crear el ítem de la compra
                    ItemCompra::create([
                        'cantidad' => $item['cantidad'],
                        'precio' => $item['precio'],
                        'existencia_id' => $item['existencia']->id,
                        'compra_id' => $compra->id,
                    ]);

                    // Incrementar la cantidad en la existencia
                    $existencia->increment('cantidad', $item['cantidad']);
                }
            });

            LivewireAlert::title($this->accion === 'edit' ? 'Compra actualizada con éxito.' : 'Compra registrada con éxito.')->success()->show();
            $this->cerrarModal();
        } catch (\Exception $e) {
            LivewireAlert::title('Error al guardar la compra: ' . $e->getMessage())->error()->show();
        }
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->detalleModal = false;
        $this->reset(['fecha', 'observaciones', 'proveedor_id', 'personal_id', 'compraId', 'items', 'existencia_id', 'item_cantidad', 'item_precio', 'existenciasDisponibles', 'compraSeleccionada']);
        $this->resetErrorBag();
    }
}