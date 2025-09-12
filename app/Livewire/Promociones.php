<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ItemPromo;
use App\Models\Cliente;
use App\Models\Promo;
use Illuminate\Support\Str;

class Promociones extends Component
{
    public $itemPromos;

    // Props para modal
    public $modal = false;
    public $clientesSeleccionados = [];
    public $promosSeleccionadas = [];
    public $fechaAsignacion;
    public $codigo;

    public function render()
    {
        $this->itemPromos = ItemPromo::with(['cliente', 'promo'])->get();
        $clientes = Cliente::all();
        $promos   = Promo::all();

        return view('livewire.promociones', [
            'itemPromos' => $this->itemPromos,
            'clientes'   => $clientes,
            'promos'     => $promos,
        ]);
    }

    // Abrir modal
    public function abrirModal()
    {
        $this->reset(['clientesSeleccionados', 'promosSeleccionadas', 'fechaAsignacion', 'codigo']);
        $this->codigo = strtoupper(Str::random(6)); // Genera código único para el lote
        $this->fechaAsignacion = now()->format('Y-m-d');
        $this->modal = true;
    }

    // Cerrar modal
    public function cerrarModal()
    {
        $this->modal = false;
    }

    // Guardar lote
    public function guardarLote()
    {
        $this->validate([
            'clientesSeleccionados' => 'required|array|min:1',
            'promosSeleccionadas'   => 'required|array|min:1',
            'fechaAsignacion'       => 'required|date',
        ]);

        foreach ($this->clientesSeleccionados as $clienteId) {
            foreach ($this->promosSeleccionadas as $promoId) {
                // Evitar duplicados para un mismo cliente y promo en el mismo código
                $exists = ItemPromo::where('cliente_id', $clienteId)
                    ->where('promo_id', $promoId)
                    ->where('codigo', $this->codigo)
                    ->exists();

                if (!$exists) {
                    ItemPromo::create([
                        'cliente_id'       => $clienteId,
                        'promo_id'         => $promoId,
                        'codigo'           => $this->codigo,
                        'fecha_asignacion' => $this->fechaAsignacion,
                    ]);
                }
            }
        }

        $this->cerrarModal();
    }
}
