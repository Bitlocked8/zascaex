<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Existencia;
use App\Models\Reposicion;
use App\Models\Sucursal;
use App\Models\Proveedor;
use App\Models\Personal;

class Stocks extends Component
{
    use WithFileUploads;

    // Propiedades para filtrar y mostrar
    public $existencias;
    public $reposiciones;

    public $proveedores;

    public $selectedSucursal;
    public $personal;
    // Modal de reposición
    public $modal = false;
    public $reposicion_id = null;
    public $existencia_id;
    public $personal_id;
    public $proveedor_id;
    public $cantidad;
    public $precio_unitario;
    public $imagen;
    public $fecha;
    public $observaciones;
    public $accion = 'create';

    public $modalConfigGlobal = false;
    public $configExistencias = []; // Array para guardar los valores de cada existencia temporalmente
    public $sucursales;


    protected $rules = [
        'existencia_id' => 'required|exists:existencias,id',
        'personal_id' => 'required|exists:personals,id',
        'proveedor_id' => 'nullable|exists:proveedors,id',
        'cantidad' => 'required|integer|min:1',
        'precio_unitario' => 'nullable|numeric|min:0',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        'fecha' => 'required|date',
        'observaciones' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->sucursales = Sucursal::all();
        $this->proveedores = Proveedor::all();
        $this->personal = Personal::all();
        $this->selectedSucursal = $this->sucursales->first()->id ?? null;

        $this->cargarExistencias();
        $this->cargarReposiciones();
    }

  public function cargarExistencias()
{
    $this->existencias = Existencia::with('existenciable')
        ->orderBy('id', 'asc')
        ->get()
        ->map(function ($ex) {
            // Solo mostrar la cantidad disponible de la existencia
            $ex->stock_real = $ex->cantidad;
            return $ex;
        });
}





    public function cargarReposiciones()
    {
        $this->reposiciones = Reposicion::with(['existencia.existenciable', 'personal', 'proveedor'])
            ->orderBy('fecha', 'desc')
            ->get();
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'reposicion_id',
            'existencia_id',
            'personal_id',
            'proveedor_id',
            'cantidad',
            'precio_unitario',
            'imagen',
            'fecha',
            'observaciones'
        ]);

        $this->accion = $accion;
        $this->fecha = now()->format('Y-m-d');

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $reposicion = Reposicion::findOrFail($id);
        $this->reposicion_id = $reposicion->id;
        $this->existencia_id = $reposicion->existencia_id;
        $this->personal_id = $reposicion->personal_id;
        $this->proveedor_id = $reposicion->proveedor_id;
        $this->cantidad = $reposicion->cantidad;
        $this->precio_unitario = $reposicion->precio_unitario;
        $this->imagen = $reposicion->imagen;
        $this->fecha = $reposicion->fecha;
        $this->observaciones = $reposicion->observaciones;
    }

    public function guardar()
    {
        // Validación
        if (is_object($this->imagen)) {
            $rules = $this->rules;
        } else {
            $rules = $this->rules;
            $rules['imagen'] = 'nullable';
        }
        $this->validate($rules);

        // Imagen
        if (is_object($this->imagen)) {
            $imagenPath = $this->imagen->store('reposiciones', 'public');
        } else {
            $imagenPath = $this->reposicion_id
                ? Reposicion::find($this->reposicion_id)->imagen
                : null;
        }

        // Si es edición, obtener la cantidad anterior
        $cantidadAnterior = 0;
        if ($this->reposicion_id) {
            $cantidadAnterior = Reposicion::find($this->reposicion_id)->cantidad;
        }

        // Crear o actualizar la reposición
        $reposicion = Reposicion::updateOrCreate(
            ['id' => $this->reposicion_id],
            [
                'existencia_id'   => $this->existencia_id,
                'personal_id'     => $this->personal_id,
                'proveedor_id'    => $this->proveedor_id,
                'cantidad'        => $this->cantidad,
                'precio_unitario' => $this->precio_unitario,
                'imagen'          => $imagenPath,
                'fecha'           => $this->fecha,
                'observaciones'   => $this->observaciones,
            ]
        );

        // Actualizar existencia correctamente
        $existencia = Existencia::find($this->existencia_id);
        $diferencia = $this->cantidad - $cantidadAnterior;
        $existencia->cantidad += $diferencia;
        $existencia->save();

        $this->cerrarModal();
        $this->cargarReposiciones();
        $this->cargarExistencias();
    }



    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'reposicion_id',
            'existencia_id',
            'personal_id',
            'proveedor_id',
            'cantidad',
            'precio_unitario',
            'imagen',
            'fecha',
            'observaciones'
        ]);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.stocks', [
            'existencias' => $this->existencias,
            'reposiciones' => $this->reposiciones,
            'personal' => $this->personal,
        ]);
    }

    public function abrirModalConfigGlobal()
    {
        $this->configExistencias = $this->existencias->mapWithKeys(function ($ex) {
            return [
                $ex->id => [
                    'cantidad_minima' => $ex->cantidadMinima,
                    'sucursal_id' => $ex->sucursal_id
                ]
            ];
        })->toArray();

        $this->modalConfigGlobal = true;
    }



    public function guardarConfigGlobal()
    {
        foreach ($this->configExistencias as $id => $config) {
            $existencia = Existencia::find($id);
            if ($existencia) {
                $existencia->update([
                    'cantidadMinima' => $config['cantidad_minima'],
                    'sucursal_id' => $config['sucursal_id'],
                ]);
            }
        }

        $this->modalConfigGlobal = false;
        $this->cargarExistencias(); // refresca la lista
    }
}
