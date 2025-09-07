<?php

namespace App\Livewire;

use App\Models\Elaboracion as ModelElaboracion;
use App\Models\Personal;
use App\Models\Existencia;
use App\Models\Sucursal;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Elaboracion extends Component
{
    use WithPagination;

    public $search = '';
    public $modal = false;
    public $accion = 'create';

    public $elaboracionId = null;
    public $fecha_elaboracion;
    public $personal_id;
    public $existencia_entrada_id;
    public $existencia_salida_id;
    public $cantidad_entrada;
    public $cantidad_salida;
    public $observaciones;
    public $modalDetalle = false;
    public $elaboracionSeleccionada = [];
    public $personales = [];
    public $existencias_preforma = [];
    public $existencias_base = [];

    public $sucursal_id;
    public $sucursales = []; // Para cargar en el select

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'fecha_elaboracion' => 'required|date',
        'personal_id' => 'required|exists:personals,id',
        'existencia_entrada_id' => 'required|exists:existencias,id',
        'cantidad_entrada' => 'required|integer|min:1',
        'cantidad_salida' => 'nullable|integer|min:0',
        'observaciones' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->personales = Personal::all();
        $this->sucursales = Sucursal::all();

        // $this->existencias_preforma = Existencia::where('existenciable_type', 'App\\Models\\Preforma')->get();
        // $this->existencias_base = Existencia::where('existenciable_type', 'App\\Models\\Base')->get();
    }

    public function preformasSucursal()
    {
        if ($this->sucursal_id) {
            $this->existencias_preforma = Existencia::where('existenciable_type', 'App\\Models\\Preforma')
                ->where('sucursal_id', $this->sucursal_id)
                ->get();
        } else {
            $this->existencias_preforma = [];
        }

        // Limpiar valores anteriores
        $this->existencia_entrada_id = null;
        $this->existencia_salida_id = null;
        $this->existencias_base = [];
    }

    public function basesPreformas()
    {
        // Limpiar existencia_salida seleccionada
        $this->existencia_salida_id = null;

        // Verificar si se ha seleccionado preforma y sucursal
        if (!$this->existencia_entrada_id || !$this->sucursal_id) {
            $this->existencias_base = [];
            return;
        }

        $entrada = Existencia::find($this->existencia_entrada_id);

        if (!$entrada) {
            $this->existencias_base = [];
            return;
        }

        // Obtener existencias base de la misma sucursal y asociadas al mismo producto de la preforma
        $this->existencias_base = Existencia::where('existenciable_type', 'App\\Models\\Base')
            ->where('sucursal_id', $this->sucursal_id)
            ->where('existenciable_id', $entrada->existenciable_id)
            ->get();
    }





    public function render()
    {
        $elaboraciones = ModelElaboracion::with(['personal'])
            ->when($this->search, function ($query) {
                $query->whereDate('fecha_elaboracion', $this->search)
                    ->orWhereHas('personal', function ($q) {
                        $q->where('nombre', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate(5);

        return view('livewire.elaboracion', compact('elaboraciones'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal()
    {
        $this->resetForm();
        $this->accion = 'create';
        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function editar($id)
    {
        $elaboracion = ModelElaboracion::findOrFail($id);
        $this->elaboracionId = $elaboracion->id;
        $this->fecha_elaboracion = $elaboracion->fecha_elaboracion;
        $this->personal_id = $elaboracion->personal_id;
        $this->existencia_entrada_id = $elaboracion->existencia_entrada_id;
        $this->cantidad_entrada = $elaboracion->cantidad_entrada;
        $this->cantidad_salida = $elaboracion->cantidad_salida;
        $this->observaciones = $elaboracion->observaciones;

        $this->accion = 'edit';
        $this->modal = true;
    }

    // public function guardar()
    // {
    //     $this->validate();

    //     try {
    //         if ($this->accion === 'edit' && $this->elaboracionId) {
    //             ModelElaboracion::findOrFail($this->elaboracionId)->update([
    //                 'fecha_elaboracion' => $this->fecha_elaboracion,
    //                 'personal_id' => $this->personal_id,
    //                 'existencia_entrada_id' => $this->existencia_entrada_id,
    //                 'cantidad_entrada' => $this->cantidad_entrada,
    //                 'cantidad_salida' => $this->cantidad_salida,
    //                 'observaciones' => $this->observaciones,
    //             ]);
    //             LivewireAlert::title('Elaboración actualizada con éxito.')->success()->show();
    //         } else {
    //             ModelElaboracion::create([
    //                 'fecha_elaboracion' => $this->fecha_elaboracion,
    //                 'personal_id' => $this->personal_id,
    //                 'existencia_entrada_id' => $this->existencia_entrada_id,
    //                 'cantidad_entrada' => $this->cantidad_entrada,
    //                 'cantidad_salida' => $this->cantidad_salida,
    //                 'observaciones' => $this->observaciones,
    //             ]);
    //             LivewireAlert::title('Elaboración registrada con éxito.')->success()->show();
    //         }

    //         $this->cerrarModal();
    //     } catch (\Exception $e) {
    //         LivewireAlert::title('Error: ' . $e->getMessage())->error()->show();
    //     }
    // }
    public function guardar()
    {
        $this->validate();
    
        try {
            // Buscar existencia de entrada (preforma)
            $entrada = Existencia::findOrFail($this->existencia_entrada_id);
    
            // Validación: cantidad_entrada no puede ser mayor a la existencia actual
            if ($this->cantidad_entrada > $entrada->cantidad) {
                $this->addError('cantidad_entrada', 'La cantidad de entrada no puede ser mayor al stock disponible de preformas (' . $entrada->cantidad . ').');
                return;
            }
    
            // Validación: cantidad_salida no puede ser mayor que la cantidad_entrada
            if (!is_null($this->cantidad_salida) && $this->cantidad_salida > $this->cantidad_entrada) {
                $this->addError('cantidad_salida', 'La cantidad de salida no puede ser mayor que la cantidad de entrada.');
                return;
            }
    
            // Buscar o crear existencia de base asociada
            $base = Existencia::where('existenciable_type', 'App\\Models\\Base')
                ->where('sucursal_id', $this->sucursal_id)
                ->where('existenciable_id', $entrada->existenciable_id)
                ->first();
    
            if (!$base) {
                $base = Existencia::create([
                    'existenciable_type' => 'App\\Models\\Base',
                    'existenciable_id' => $entrada->existenciable_id,
                    'sucursal_id' => $this->sucursal_id,
                    'cantidad' => 0,
                    'descripcion' => 'Generada desde elaboración',
                ]);
            }
    
            // Guardar ID de salida para relación
            $this->existencia_salida_id = $base->id;
    
            // Guardar o actualizar elaboración
            if ($this->accion === 'edit' && $this->elaboracionId) {
                ModelElaboracion::findOrFail($this->elaboracionId)->update([
                    'fecha_elaboracion' => $this->fecha_elaboracion,
                    'personal_id' => $this->personal_id,
                    'existencia_entrada_id' => $this->existencia_entrada_id,
                    'existencia_salida_id' => $this->existencia_salida_id,
                    'cantidad_entrada' => $this->cantidad_entrada,
                    'cantidad_salida' => $this->cantidad_salida,
                    'observaciones' => $this->observaciones,
                ]);
            } else {
                ModelElaboracion::create([
                    'fecha_elaboracion' => $this->fecha_elaboracion,
                    'personal_id' => $this->personal_id,
                    'existencia_entrada_id' => $this->existencia_entrada_id,
                    'existencia_salida_id' => $this->existencia_salida_id,
                    'cantidad_entrada' => $this->cantidad_entrada,
                    'cantidad_salida' => $this->cantidad_salida,
                    'observaciones' => $this->observaciones,
                ]);
            }
    
            // Actualizar existencias
            $entrada->cantidad -= $this->cantidad_entrada;
            $entrada->save();
    
            if (!is_null($this->cantidad_salida)) {
                $base->cantidad += $this->cantidad_salida;
                $base->save();
            }
    
            LivewireAlert::title('Elaboración registrada con éxito.')->success()->show();
            $this->cerrarModal();
    
        } catch (\Exception $e) {
            LivewireAlert::title('Error: ' . $e->getMessage())->error()->show();
        }
    }
    

    private function resetForm()
    {
        $this->reset([
            'elaboracionId',
            'fecha_elaboracion',
            'personal_id',
            'existencia_entrada_id',
            'cantidad_entrada',
            'cantidad_salida',
            'observaciones',
        ]);
    }
    public function modaldetalle($id)
    {
        $this->elaboracionSeleccionada = ModelElaboracion::findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->elaboracionSeleccionada = null;
    }
}
