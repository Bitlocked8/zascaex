<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etiqueta;
use App\Models\Existencia;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Auth;

class ClienteEtiquetas extends Component
{
    use WithFileUploads;

    public $modal = false;

    public $etiqueta_id;
    public $descripcion;
    public $capacidad;
    public $unidad;
    public $estado = 1;
    public $imagen;
    public $imagenExistente;

    protected $rules = [
        'descripcion' => 'required|string|max:255',
        'capacidad' => 'nullable|string|max:255',
        'unidad' => 'nullable|string|max:10',
        'estado' => 'required|boolean',
        'imagen' => 'nullable|image|max:5120',
    ];

    public function mount()
    {
        $this->reset();
    }

    public function render()
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return view('livewire.cliente-etiquetas', ['etiquetas' => collect()]);
        }

        $etiquetas = Etiqueta::where('cliente_id', $cliente->id)
            ->with('existencias')
            ->get();

        return view('livewire.cliente-etiquetas', compact('etiquetas'));
    }

    public function abrirModal($id = null)
    {
        $this->reset([
            'etiqueta_id',
            'descripcion',
            'capacidad',
            'unidad',
            'estado',
            'imagen',
            'imagenExistente'
        ]);

        if ($id) {
            $etiqueta = Etiqueta::where('id', $id)
                ->where('cliente_id', Auth::user()->cliente->id)
                ->firstOrFail();

            $this->etiqueta_id = $etiqueta->id;
            $this->descripcion = $etiqueta->descripcion;
            $this->capacidad = $etiqueta->capacidad;
            $this->unidad = $etiqueta->unidad;
            $this->estado = $etiqueta->estado;
            $this->imagenExistente = $etiqueta->imagen;
        }

        $this->modal = true;
    }

    public function guardar()
    {
        $rules = $this->rules;

        if (!$this->imagen) {
            unset($rules['imagen']);
        }

        $this->validate($rules);

        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            session()->flash('error', 'No se encontró el cliente.');
            return;
        }

        if ($this->imagen) {
            $imagenPath = $this->imagen->store('etiquetas', 'public');
        } else {
            $imagenPath = $this->imagenExistente;
        }

        $etiqueta = Etiqueta::updateOrCreate(
            ['id' => $this->etiqueta_id],
            [
                'descripcion' => $this->descripcion,
                'capacidad' => $this->capacidad,
                'unidad' => $this->unidad ?: null,
                'estado' => $this->estado,
                'imagen' => $imagenPath,
                'cliente_id' => $cliente->id,
                'tipo' => 1,
            ]
        );

        if (!$this->etiqueta_id) {
            $sucursal_id = $cliente->sucursal_id;

            if (!$sucursal_id || !Sucursal::find($sucursal_id)) {
                $sucursal_id = 1;
            }

            Existencia::create([
                'existenciable_type' => Etiqueta::class,
                'existenciable_id' => $etiqueta->id,
                'sucursal_id' => $sucursal_id,
                'cantidad' => 0,
                'cantidadMinima' => 0,
            ]);
        }

        $this->cerrarModal();
    }

    public function eliminar($id)
    {
        $etiqueta = Etiqueta::where('id', $id)
            ->where('cliente_id', Auth::user()->cliente->id)
            ->firstOrFail();

        $etiqueta->delete();
    }

    public function cerrarModal()
    {
        $this->modal = false;

        $this->reset([
            'etiqueta_id',
            'descripcion',
            'capacidad',
            'unidad',
            'estado',
            'imagen',
            'imagenExistente'
        ]);

        $this->resetErrorBag();
    }
}
