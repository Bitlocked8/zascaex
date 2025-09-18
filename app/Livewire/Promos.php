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

    // Campos
    public $nombre;
    public $tipo_descuento = 'porcentaje';
    public $valor_descuento;
    public $uso_maximo;
    public $fecha_asignada;
    public $fecha_expiracion;
    public $activo = 1;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'tipo_descuento' => 'required|in:porcentaje,monto',
        'valor_descuento' => 'required|numeric|min:0',
        'uso_maximo' => 'nullable|integer|min:0',
        'fecha_asignada' => 'nullable|date',
        'fecha_expiracion' => 'nullable|date|after_or_equal:fecha_asignada',
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
            'uso_maximo',
            'fecha_asignada',
            'fecha_expiracion',
            'activo',
        ]);

        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        } else {
            // 👇 Solo si es crear, ponemos la fecha de hoy
            $this->fecha_asignada = now()->format('Y-m-d');
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
        $this->uso_maximo = $promo->uso_maximo;
        $this->fecha_asignada = $promo->fecha_asignada?->format('Y-m-d');
        $this->fecha_expiracion = $promo->fecha_expiracion?->format('Y-m-d');
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
                'uso_maximo' => $this->uso_maximo,
                'fecha_asignada' => $this->fecha_asignada ?? now()->format('Y-m-d'),
                'fecha_expiracion' => $this->fecha_expiracion,
                'activo' => $this->activo,
            ]
        );

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'promo_id',
            'nombre',
            'tipo_descuento',
            'valor_descuento',
            'uso_maximo',
            'fecha_asignada',
            'fecha_expiracion',
            'activo'
        ]);
        $this->resetErrorBag();
    }
}
