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

    public function rules()
    {
        return [
            'movil' => 'required|string|max:100',
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'anio' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:50',
            'placa' => 'nullable|string|max:20|unique:coches,placa,' . ($this->cocheId ?? 'NULL'),
            'estado' => 'required|boolean',
        ];
    }

    protected $messages = [
        'movil.required' => 'El número de móvil es obligatorio.',
        'placa.required' => 'La placa es obligatoria.',
        'placa.unique' => 'Ya existe un coche con esta placa.',
        'estado.boolean' => 'El estado debe ser válido.',
    ];

    public function render()
    {
        $coches = ModeloCoche::when($this->search, function ($query) {
            $query->where('marca', 'like', '%' . $this->search . '%')
                ->orWhere('modelo', 'like', '%' . $this->search . '%')
                ->orWhere('placa', 'like', '%' . $this->search . '%');
        })->latest()->get();

        return view('livewire.coche', compact('coches'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal($accion)
    {
        $this->reset(['movil', 'marca', 'modelo', 'anio', 'color', 'placa', 'estado', 'cocheId', 'cocheSeleccionado']);
        $this->accion = $accion;
        $this->estado = 1;
        $this->modal = true;
        $this->detalleModal = false;
        $this->resetErrorBag();
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
        $this->resetErrorBag();
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

        if (!$this->movil) {
            $this->movil = 'M-' . mt_rand(1000, 9999); // Generar móvil automáticamente si está vacío
        }

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
            session()->flash('message', 'Coche actualizado correctamente.');
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
            session()->flash('message', 'Coche creado correctamente.');
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
