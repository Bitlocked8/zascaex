<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Promo;

class Promos extends Component
{
    public $modal = false;
    public $accion = 'create';
    public $promo_id = null;

    public $search = '';

    public $nombre, $tipo_descuento = 'porcentaje', $valor_descuento, $fecha_inicio, $fecha_fin, $activo = 1;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'tipo_descuento' => 'required|in:porcentaje,monto',
        'valor_descuento' => 'required|numeric|min:0',
        'fecha_inicio' => 'nullable|date',
        'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        'activo' => 'boolean',
    ];

    public function render()
    {
        $promos = Promo::where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->get();

        return view('livewire.promos', compact('promos'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'promo_id',
            'nombre',
            'tipo_descuento',
            'valor_descuento',
            'fecha_inicio',
            'fecha_fin',
            'activo',
        ]);

        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $promo = Promo::findOrFail($id);

        $this->promo_id = $promo->id;
        $this->nombre = $promo->nombre;
        $this->tipo_descuento = $promo->tipo_descuento;
        $this->valor_descuento = $promo->valor_descuento;
        $this->fecha_inicio = $promo->fecha_inicio?->format('Y-m-d');
        $this->fecha_fin = $promo->fecha_fin?->format('Y-m-d');
        $this->activo = $promo->activo;

        $this->accion = 'edit';
    }

    public function guardar()
    {
        $this->validate();

        Promo::updateOrCreate(
            ['id' => $this->promo_id],
            [
                'nombre' => $this->nombre,
                'tipo_descuento' => $this->tipo_descuento,
                'valor_descuento' => $this->valor_descuento,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'activo' => $this->activo,
            ]
        );

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['promo_id', 'nombre', 'tipo_descuento', 'valor_descuento', 'fecha_inicio', 'fecha_fin', 'activo']);
        $this->resetErrorBag();
    }
}
