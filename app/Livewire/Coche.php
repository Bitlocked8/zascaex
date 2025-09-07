<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Coche as ModeloCoche;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Coche extends Component
{
    use WithPagination;
    // use LivewireAlert;

    public $search = '';
    public $modal = false;
    public $detalleModal = false;
    public $accion = 'create';
    public $cocheId = null;
    public $movil = '';
    public $marca = '';
    public $modelo = '';
    public $anio = '';
    public $color = '';
    public $placa = '';
    public $estado = 1;
    public $cocheSeleccionado = null;

    protected $paginationTheme = 'tailwind';

    // protected $rules = [
    //     'movil' => 'required|integer',
    //     'marca' => 'required|string|max:255',
    //     'modelo' => 'required|string|max:255',
    //     'anio' => 'required|integer|min:1900|max:' . date('Y'),
    //     'color' => 'required|string|max:255',
    //     'placa' => 'required|string|max:20|unique:coches,placa',
    //     'estado' => 'required|boolean',
    // ];

    public function render()
    {
        $coches = ModeloCoche::when($this->search, function ($query) {
            $query->where('marca', 'like', '%' . $this->search . '%')
                  ->orWhere('modelo', 'like', '%' . $this->search . '%')
                  ->orWhere('placa', 'like', '%' . $this->search . '%');
        })->paginate(5);

        return view('livewire.coche', compact('coches'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal($accion)
    {
        $this->reset(['movil', 'marca', 'modelo', 'anio', 'color', 'placa', 'estado', 'cocheId']);
        $this->accion = $accion;
        $this->estado = 1;
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function editarCoche($id)
    {
        $coche = ModeloCoche::findOrFail($id);
        $this->cocheId = $coche->id;
        $this->movil = $coche->movil;
        $this->marca = $coche->marca;
        $this->modelo = $coche->modelo;
        $this->anio = $coche->anio;
        $this->color = $coche->color;
        $this->placa = $coche->placa;
        $this->estado = $coche->estado;
        $this->accion = 'edit';
        $this->modal = true;
        $this->detalleModal = false;

        // Ajustar la validación de placa para permitir el valor actual
        // $this->rules['placa'] = 'required|string|max:20|unique:coches,placa,' . $coche->id;
    }

    public function verDetalle($id)
    {
        $this->cocheSeleccionado = ModeloCoche::findOrFail($id);
        $this->modal = false;
        $this->detalleModal = true;
    }

    public function guardarCoche()
    {
        $this->validate();

        try {
            if ($this->accion === 'edit' && $this->cocheId) {
                $coche = ModeloCoche::findOrFail($this->cocheId);
                $coche->update([
                    'movil' => $this->movil,
                    'marca' => $this->marca,
                    'modelo' => $this->modelo,
                    'anio' => $this->anio,
                    'color' => $this->color,
                    'placa' => $this->placa,
                    'estado' => $this->estado,
                ]);
                LivewireAlert::title('Coche actualizado con éxito.')
                    ->success()
                    ->show();
            } else {
                ModeloCoche::create([
                    'movil' => $this->movil,
                    'marca' => $this->marca,
                    'modelo' => $this->modelo,
                    'anio' => $this->anio,
                    'color' => $this->color,
                    'placa' => $this->placa,
                    'estado' => $this->estado,
                ]);
                LivewireAlert::title('Coche registrado con éxito.')
                    ->success()
                    ->show();
            }

            $this->cerrarModal();
        } catch (\Exception $e) {
            LivewireAlert::title('Ocurrió un error: ' . $e->getMessage())
                ->error()
                ->show();
        }
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->detalleModal = false;
        $this->reset(['movil', 'marca', 'modelo', 'anio', 'color', 'placa', 'estado', 'cocheId', 'cocheSeleccionado']);
        $this->resetErrorBag();
        // $this->rules['placa'] = 'required|string|max:20|unique:coches,placa'; // Restaurar regla original
    }
}