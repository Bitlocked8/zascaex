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
    public $modalDetalle = false;

    public $bases = [];
    public $tapas = [];
    public $productos = [];
    public $personals = [];
    public $sucursalSeleccionada = null;
    public $embotellado_id = null;
    public $existencia_base_id = '';
    public $existencia_tapa_id = '';
    public $existencia_producto_id = '';
    public $personal_id = '';
    public $cantidad_base_usada = 0;
    public $cantidad_tapa_usada = 0;
    public $cantidad_generada = 0;
    public $mermaBase = 0;
    public $mermaTapa = 0;
    public $fecha_embotellado = '';
    public $observaciones = '';
    public $codigo = '';
    public $estado = 'pendiente';
    public $accion = 'create';
    public $embotelladoSeleccionado = null;

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
        'observaciones' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->cargarExistencias();
        $this->personals = Personal::all();
    }

    public function cargarExistencias($sucursalId = null)
    {
        $queryBases = Existencia::with('existenciable')->where('existenciable_type', Base::class);
        $queryTapas = Existencia::with('existenciable')->where('existenciable_type', Tapa::class);
        $queryProductos = Existencia::with('existenciable')->where('existenciable_type', Producto::class);

        if ($sucursalId) {
            $queryBases->where('sucursal_id', $sucursalId);
            $queryTapas->where('sucursal_id', $sucursalId);
            $queryProductos->where('sucursal_id', $sucursalId);
        }

        $this->bases = $queryBases->get();
        $this->tapas = $queryTapas->get();
        $this->productos = $queryProductos->get();
    }

    public function filtrarPorSucursal($sucursalId)
    {
        $this->sucursalSeleccionada = $sucursalId;
        $this->cargarExistencias($sucursalId);
    }

    public function render()
    {
        $embotellados = ModelEmbotellado::with([
            'existenciaBase.existenciable',
            'existenciaTapa.existenciable',
            'existenciaProducto.existenciable',
            'personal'
        ])->when($this->search, function ($query) {
            $query->where('observaciones', 'like', "%{$this->search}%")
                ->orWhere('codigo', 'like', "%{$this->search}%");
        })->orderBy('id', 'desc')->get();

        return view('livewire.embotellado', compact('embotellados'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'existencia_base_id',
            'existencia_tapa_id',
            'existencia_producto_id',
            'personal_id',
            'cantidad_base_usada',
            'cantidad_tapa_usada',
            'cantidad_generada',
            'mermaBase',
            'mermaTapa',
            'observaciones',
            'codigo',
            'estado',
            'fecha_embotellado',
            'sucursalSeleccionada',
        ]);

        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        } else {
            $ultimoId = ModelEmbotellado::max('id') ?? 0;
            $this->codigo = 'E-' . date('Ymd') . '-' . ($ultimoId + 1);
            $this->estado = 'pendiente';
            $this->fecha_embotellado = date('Y-m-d');
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $embotellado = ModelEmbotellado::with([
            'existenciaBase',
            'existenciaTapa',
            'existenciaProducto',
            'personal'
        ])->findOrFail($id);

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
        $this->fecha_embotellado = $embotellado->fecha_embotellado;
        $this->observaciones = $embotellado->observaciones;
        $this->codigo = $embotellado->codigo;
        $this->estado = $embotellado->estado;
        $this->embotelladoSeleccionado = $embotellado;

        // 🔹 Asegura sucursal desde cualquiera de los tres
        $this->sucursalSeleccionada =
            $embotellado->existenciaBase->sucursal_id
            ?? $embotellado->existenciaTapa->sucursal_id
            ?? $embotellado->existenciaProducto->sucursal_id
            ?? null;

        $this->cargarExistencias($this->sucursalSeleccionada);
    }

    public function guardar()
    {
        $this->validate();

        // Obtener existencias seleccionadas
        $base = Existencia::find($this->existencia_base_id);
        $tapa = Existencia::find($this->existencia_tapa_id);
        $producto = $this->existencia_producto_id ? Existencia::find($this->existencia_producto_id) : null;

        if (!$base || !$tapa) {
            $this->addError('existencias', 'La base o la tapa seleccionada no existe.');
            return;
        }

        // Validación de sucursal
        if ($base->sucursal_id !== $tapa->sucursal_id || ($producto && $producto->sucursal_id !== $base->sucursal_id)) {
            $this->addError('sucursal_id', 'Base, tapa y producto deben pertenecer a la misma sucursal.');
            return;
        }

        if ($this->accion === 'create') {
            // Validar cantidades disponibles
            if ($this->cantidad_base_usada > $base->cantidad) {
                $this->addError('cantidad_base_usada', 'No puedes usar más base que la disponible.');
                return;
            }
            if ($this->cantidad_tapa_usada > $tapa->cantidad) {
                $this->addError('cantidad_tapa_usada', 'No puedes usar más tapas que las disponibles.');
                return;
            }

            // Crear nuevo embotellado
            $embotellado = ModelEmbotellado::create([
                'codigo' => $this->codigo,
                'estado' => $this->estado,
                'existencia_base_id' => $this->existencia_base_id,
                'existencia_tapa_id' => $this->existencia_tapa_id,
                'existencia_producto_id' => $this->existencia_producto_id ?: null,
                'personal_id' => $this->personal_id,
                'cantidad_base_usada' => $this->cantidad_base_usada,
                'cantidad_tapa_usada' => $this->cantidad_tapa_usada,
                'cantidad_generada' => $this->cantidad_generada,
                'mermaBase' => $this->mermaBase,
                'mermaTapa' => $this->mermaTapa,
                'fecha_embotellado' => $this->fecha_embotellado,
                'observaciones' => $this->observaciones
            ]);

            // Actualizar existencias
            $base->decrement('cantidad', $this->cantidad_base_usada);
            $tapa->decrement('cantidad', $this->cantidad_tapa_usada);
            if ($producto && $this->cantidad_generada > 0) {
                $producto->increment('cantidad', $this->cantidad_generada);
            }
        } else {
            // Editar embotellado existente
            $embotellado = ModelEmbotellado::findOrFail($this->embotellado_id);

            // Restaurar stock del embotellado anterior
            $oldBase = Existencia::find($embotellado->existencia_base_id);
            $oldTapa = Existencia::find($embotellado->existencia_tapa_id);
            $oldProducto = $embotellado->existencia_producto_id ? Existencia::find($embotellado->existencia_producto_id) : null;

            $oldBase && $oldBase->increment('cantidad', $embotellado->cantidad_base_usada);
            $oldTapa && $oldTapa->increment('cantidad', $embotellado->cantidad_tapa_usada);
            $oldProducto && $embotellado->cantidad_generada > 0 && $oldProducto->decrement('cantidad', $embotellado->cantidad_generada);

            // Validar cantidades disponibles para la nueva selección
            if ($this->cantidad_base_usada > $base->cantidad) {
                $this->addError('cantidad_base_usada', 'No puedes usar más base que la disponible.');
                return;
            }
            if ($this->cantidad_tapa_usada > $tapa->cantidad) {
                $this->addError('cantidad_tapa_usada', 'No puedes usar más tapas que las disponibles.');
                return;
            }

            // Actualizar embotellado
            $embotellado->update([
                'codigo' => $this->codigo,
                'estado' => $this->estado,
                'existencia_base_id' => $this->existencia_base_id,
                'existencia_tapa_id' => $this->existencia_tapa_id,
                'existencia_producto_id' => $this->existencia_producto_id ?: null,
                'personal_id' => $this->personal_id,
                'cantidad_base_usada' => $this->cantidad_base_usada,
                'cantidad_tapa_usada' => $this->cantidad_tapa_usada,
                'cantidad_generada' => $this->cantidad_generada,
                'mermaBase' => $this->mermaBase,
                'mermaTapa' => $this->mermaTapa,
                'fecha_embotellado' => $this->fecha_embotellado,
                'observaciones' => $this->observaciones
            ]);

            // Aplicar nuevo consumo
            $base->decrement('cantidad', $this->cantidad_base_usada);
            $tapa->decrement('cantidad', $this->cantidad_tapa_usada);
            $newProducto = $this->existencia_producto_id ? Existencia::find($this->existencia_producto_id) : null;
            $newProducto && $this->cantidad_generada > 0 && $newProducto->increment('cantidad', $this->cantidad_generada);
        }

        $this->cerrarModal();
    }



    public function cerrarModal()
    {
        $this->modal = false;
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
            'fecha_embotellado',
            'observaciones',
            'codigo',
            'estado',
            'embotelladoSeleccionado',
            'sucursalSeleccionada',
        ]);
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
