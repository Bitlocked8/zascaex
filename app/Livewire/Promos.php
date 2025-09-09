<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Promo;

class Promos extends Component
{
    public $nombre;
    public $tipo_descuento = 'porcentaje';
    public $valor_descuento;
    public $fecha_inicio;
    public $fecha_fin;
    public $activo = true;

    public $modal = false;
    public $promoId = null;

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
        $promos = Promo::whereNull('cliente_id')->orderBy('id', 'desc')->get();
        return view('livewire.promos', compact('promos'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->resetErrorBag();
        $this->reset(['nombre','tipo_descuento','valor_descuento','fecha_inicio','fecha_fin','activo','promoId']);

        if($accion === 'edit' && $id){
            $promo = Promo::findOrFail($id);
            $this->promoId = $promo->id;
            $this->nombre = $promo->nombre;
            $this->tipo_descuento = $promo->tipo_descuento;
            $this->valor_descuento = $promo->valor_descuento;
            $this->fecha_inicio = $promo->fecha_inicio?->format('Y-m-d');
            $this->fecha_fin = $promo->fecha_fin?->format('Y-m-d');
            $this->activo = $promo->activo;
        }

        $this->modal = true;
    }

    public function guardarPromo()
    {
        $this->validate();

        Promo::updateOrCreate(
            ['id' => $this->promoId],
            [
                'nombre' => $this->nombre,
                'tipo_descuento' => $this->tipo_descuento,
                'valor_descuento' => $this->valor_descuento,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'activo' => $this->activo,
                'cliente_id' => null,
            ]
        );

        $this->modal = false;
    }

    public function toggleActivo($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->activo = !$promo->activo;
        $promo->save();
    }

    public function cerrarModal()
    {
        $this->modal = false;
    }
}
