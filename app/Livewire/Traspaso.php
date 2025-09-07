<?php

namespace App\Livewire;

use App\Models\Traspaso as ModelTraspaso;
use App\Models\Existencia;
use App\Models\Personal;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Traspaso extends Component
{
    use WithPagination;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $accion = 'create';

    public $traspasoId;
    public $fecha_traspaso;
    public $personal_id;
    public $existencia_origen_id;
    public $existencia_destino_id;
    public $cantidad;
    public $observaciones;

    public $traspasoSeleccionado = [];
    public $personales = [];
    public $existencias_origen = [];
    public $existencias_destino = [];

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'fecha_traspaso' => 'required|date',
        'personal_id' => 'required|exists:personals,id',
        'existencia_origen_id' => 'required|exists:existencias,id',
        'existencia_destino_id' => 'required|exists:existencias,id',
        'cantidad' => 'required|integer|min:1',
        'observaciones' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->personales = Personal::all();
        $this->existencias_origen = Existencia::all();
        $this->existencias_destino = Existencia::all();
    }

    public function render()
    {
        $traspasos = ModelTraspaso::with(['personal'])
            ->when($this->search, function ($query) {
                $query->whereDate('fecha_traspaso', $this->search)
                    ->orWhereHas('personal', function ($q) {
                        $q->where('nombres', 'like', "%{$this->search}%");
                    });
            })
            ->latest()
            ->paginate(5);

        return view('livewire.traspaso', compact('traspasos'));
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

    public function guardar()
    {
        $this->validate();

        try {
            $origen = Existencia::findOrFail($this->existencia_origen_id);
            $destino = Existencia::findOrFail($this->existencia_destino_id);

            if ($origen->sucursal_id === $destino->sucursal_id) {
                $this->addError('existencia_destino_id', 'La sucursal de destino debe ser diferente a la de origen.');
                return;
            }

            if ($this->cantidad > $origen->cantidad) {
                $this->addError('cantidad', 'Stock insuficiente en la existencia de origen.');
                return;
            }

            ModelTraspaso::create([
                'fecha_traspaso' => $this->fecha_traspaso,
                'personal_id' => $this->personal_id,
                'existencia_origen_id' => $this->existencia_origen_id,
                'existencia_destino_id' => $this->existencia_destino_id,
                'cantidad' => $this->cantidad,
                'observaciones' => $this->observaciones,
            ]);

            $origen->cantidad -= $this->cantidad;
            $destino->cantidad += $this->cantidad;
            $origen->save();
            $destino->save();

            LivewireAlert::title('Traspaso registrado con éxito')->success()->show();
            $this->cerrarModal();

        } catch (\Exception $e) {
            LivewireAlert::title('Error: ' . $e->getMessage())->error()->show();
        }
    }

    public function editar($id)
    {
        $registro = ModelTraspaso::findOrFail($id);

        $this->traspasoId = $registro->id;
        $this->fecha_traspaso = $registro->fecha_traspaso;
        $this->personal_id = $registro->personal_id;
        $this->existencia_origen_id = $registro->existencia_origen_id;
        $this->existencia_destino_id = $registro->existencia_destino_id;
        $this->cantidad = $registro->cantidad;
        $this->observaciones = $registro->observaciones;

        $this->accion = 'edit';
        $this->modal = true;
    }

    public function modaldetalle($id)
    {
        $this->traspasoSeleccionado = ModelTraspaso::with(['personal'])->findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->traspasoSeleccionado = [];
    }

    private function resetForm()
    {
        $this->reset([
            'traspasoId', 'fecha_traspaso', 'personal_id',
            'existencia_origen_id', 'existencia_destino_id',
            'cantidad', 'observaciones'
        ]);
    }
}