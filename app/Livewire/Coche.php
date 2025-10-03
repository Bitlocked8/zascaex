<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Coche as ModeloCoche;

class Coche extends Component
{
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

    public function render()
    {
        $coches = ModeloCoche::when($this->search, function ($query) {
            $query->where('marca', 'like', '%' . $this->search . '%')
                ->orWhere('modelo', 'like', '%' . $this->search . '%')
                ->orWhere('placa', 'like', '%' . $this->search . '%');
        })->get();

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
        }

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->detalleModal = false;
        $this->reset(['movil', 'marca', 'modelo', 'anio', 'color', 'placa', 'estado', 'cocheId', 'cocheSeleccionado']);
        $this->resetErrorBag();
    }
}
