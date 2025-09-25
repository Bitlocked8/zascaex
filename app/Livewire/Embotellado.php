<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Embotellado as ModelEmbotellado;
use App\Models\Existencia;
use App\Models\Personal;
use App\Models\Base;
use App\Models\Tapa;
use App\Models\Producto;

class Embotellado extends Component
{
    public $search = '';
    public $modal = false;
    public $accion = 'create';
    public $embotellado_id = null;

    public $sucursal_producto_id;
    public $existencia_base_id;
    public $existencia_tapa_id;
    public $existencia_producto_id;
    public $personal_id;
    public $cantidad_base_usada = 0;
    public $cantidad_tapa_usada = 0;
    public $cantidad_generada = 0;
    public $mermaBase = 0;
    public $mermaTapa = 0;
    public $residuo_base = 0;
    public $residuo_tapa = 0;
    public $estado_residuo_base = 0;
    public $estado_residuo_tapa = 0;
    public $fecha_embotellado;
    public $fecha_embotellado_final;
    public $observaciones;
    public $codigo;
    public $estado = 'pendiente';
    public $modalDetalle = false;
    public $embotelladoSeleccionado = null;
    public $sucursalSeleccionada = null;
    public $cantidad_generada_original;
    public $bases = [];
    public $tapas = [];
    public $productos = [];
    public $personals = [];

    protected $rules = [
        'existencia_base_id' => 'required|exists:existencias,id',
        'existencia_tapa_id' => 'required|exists:existencias,id',
        'existencia_producto_id' => 'nullable|exists:existencias,id',
        'personal_id' => 'required|exists:personals,id',
        'cantidad_base_usada' => 'required|integer|min:1',
        'cantidad_tapa_usada' => 'required|integer|min:1',
        'cantidad_generada' => 'nullable|integer|min:0',
        'mermaBase' => 'nullable|integer|min:0',
        'mermaTapa' => 'nullable|integer|min:0',
        'residuo_base' => 'nullable|integer|min:0',
        'residuo_tapa' => 'nullable|integer|min:0',
        'observaciones' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->bases = Existencia::with('existenciable')->where('existenciable_type', Base::class)->get();
        $this->tapas = Existencia::with('existenciable')->where('existenciable_type', Tapa::class)->get();
        $this->productos = Existencia::with('existenciable')->where('existenciable_type', Producto::class)->get();
        $this->personals = Personal::all();
    }
    public function updatedEstado($value)
    {
        if ($value === 'terminado') {
            $this->fecha_embotellado_final = now();
        } else {
            $this->fecha_embotellado_final = null; // limpia si se vuelve a pendiente
        }
    }

    public function filtrarPorSucursal($sucursalId)
    {
        $this->sucursalSeleccionada = $sucursalId;

        $this->bases = Existencia::with('existenciable')
            ->where('existenciable_type', Base::class)
            ->where('sucursal_id', $sucursalId)
            ->get();

        $this->tapas = Existencia::with('existenciable')
            ->where('existenciable_type', Tapa::class)
            ->where('sucursal_id', $sucursalId)
            ->get();
    }


    public function render()
    {
        $embotellados = ModelEmbotellado::with([
            'existenciaBase.existenciable',
            'existenciaTapa.existenciable',
            'existenciaProducto.existenciable',
            'personal'
        ])->when($this->search, function ($query) {
            $query->where('codigo', 'like', "%{$this->search}%")
                ->orWhere('observaciones', 'like', "%{$this->search}%");
        })->orderBy('id', 'desc')->get();

        return view('livewire.embotellado', compact('embotellados'));
    }

    // Abrir modal crear o editar
    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'embotellado_id',
            'existencia_base_id',
            'existencia_tapa_id',
            'existencia_producto_id',
            'personal_id',
            'cantidad_base_usada',
            'cantidad_tapa_usada',
            'cantidad_generada',
            'mermaBase',
            'mermaTapa',
            'residuo_base',
            'residuo_tapa',
            'estado_residuo_base',
            'estado_residuo_tapa',
            'fecha_embotellado',
            'fecha_embotellado_final',
            'observaciones',
            'codigo',
            'estado'
        ]);

        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        } else {
            $ultimoId = ModelEmbotellado::max('id') ?? 0;
            $this->codigo = 'E-' . date('Ymd') . '-' . ($ultimoId + 1);
            $this->fecha_embotellado = now();
            $this->estado = 'pendiente';
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $embotellado = ModelEmbotellado::findOrFail($id);

        $this->embotellado_id = $embotellado->id;
        $this->existencia_base_id = $embotellado->existencia_base_id;
        $this->existencia_tapa_id = $embotellado->existencia_tapa_id;
        $this->existencia_producto_id = $embotellado->existencia_producto_id;
        $this->personal_id = $embotellado->personal_id;
        $this->cantidad_base_usada = $embotellado->cantidad_base_usada;
        $this->cantidad_tapa_usada = $embotellado->cantidad_tapa_usada;
        $this->cantidad_generada = $embotellado->cantidad_generada;
        $this->mermaBase = $embotellado->mermaBase;
        $this->mermaTapa = $embotellado->mermaTapa;
        $this->residuo_base = $embotellado->residuo_base;
        $this->residuo_tapa = $embotellado->residuo_tapa;
        $this->estado_residuo_base = $embotellado->estado_residuo_base;
        $this->estado_residuo_tapa = $embotellado->estado_residuo_tapa;
        $this->fecha_embotellado = $embotellado->fecha_embotellado;
        $this->fecha_embotellado_final = $embotellado->fecha_embotellado_final;
        $this->observaciones = $embotellado->observaciones;
        $this->codigo = $embotellado->codigo;
        $this->estado = $embotellado->estado;

        $sucursalId = $embotellado->existenciaBase->sucursal_id; // asume que todos están en la misma
        $this->filtrarPorSucursal($sucursalId);
        $this->cambiarSucursalProductos($sucursalId);
    }

    public function guardar()
    {
        $this->validate();

        // Guardar o actualizar el embotellado
        ModelEmbotellado::updateOrCreate(
            ['id' => $this->embotellado_id],
            [
                'existencia_base_id' => $this->existencia_base_id,
                'existencia_tapa_id' => $this->existencia_tapa_id,
                'existencia_producto_id' => $this->existencia_producto_id,
                'personal_id' => $this->personal_id,
                'cantidad_base_usada' => $this->cantidad_base_usada,
                'cantidad_tapa_usada' => $this->cantidad_tapa_usada,
                'cantidad_generada' => $this->cantidad_generada,
                'mermaBase' => $this->mermaBase,
                'mermaTapa' => $this->mermaTapa,
                'residuo_base' => $this->residuo_base,
                'residuo_tapa' => $this->residuo_tapa,
                'estado_residuo_base' => $this->estado_residuo_base,
                'estado_residuo_tapa' => $this->estado_residuo_tapa,
                'fecha_embotellado' => $this->fecha_embotellado,
                'fecha_embotellado_final' => $this->fecha_embotellado_final,
                'observaciones' => $this->observaciones,
                'codigo' => $this->codigo,
                'estado' => $this->estado,
            ]
        );

        // Reducir stock de Base y Tapa
        if ($this->accion === 'create') {
            Existencia::find($this->existencia_base_id)->decrement('cantidad', $this->cantidad_base_usada);
            Existencia::find($this->existencia_tapa_id)->decrement('cantidad', $this->cantidad_tapa_usada);
        }

        if ($this->accion === 'edit' && $this->existencia_producto_id) {
            $existencia = Existencia::find($this->existencia_producto_id);

            if ($existencia) {
                // Cantidad que debe tener = stock anterior - viejo + nuevo
                $nuevaCantidad = ($existencia->cantidad - $this->cantidad_generada_original) + $this->cantidad_generada;
                $existencia->update(['cantidad' => $nuevaCantidad]);

                $this->cantidad_generada_original = $this->cantidad_generada;
            }
        }

        $this->cerrarModal();
    }

    public function cambiarSucursalProductos($sucursalId)
    {
        $this->sucursal_producto_id = $sucursalId;

        $this->productos = Existencia::with('existenciable')
            ->where('existenciable_type', Producto::class)
            ->where('sucursal_id', $sucursalId)
            ->get();
    }


    public function cerrarModal()
    {
        $this->modal = false;
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $this->embotelladoSeleccionado = ModelEmbotellado::with([
            'existenciaBase.existenciable',
            'existenciaTapa.existenciable',
            'existenciaProducto.existenciable',
            'personal'
        ])->findOrFail($id);

        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->embotelladoSeleccionado = null;
    }
}
