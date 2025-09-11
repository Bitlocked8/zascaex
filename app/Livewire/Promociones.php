<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\ItemPromo;

class Promociones extends Component
{
    public $clientes;             // Lista de clientes con sus promociones
    public $searchCliente = '';   // Para búsqueda dinámica
    public $promoSeleccionada;    // Promo actualmente seleccionada para detalle
    public $modalVisible = false; // Controla si el modal está abierto

    public function render()
    {
        // Obtenemos los clientes con sus promociones, filtrando por búsqueda
        $this->clientes = Cliente::with(['itemPromos.promo'])
            ->when($this->searchCliente, function($query) {
                $query->where('nombre', 'like', '%'.$this->searchCliente.'%');
            })
            ->get();

        return view('livewire.promociones', [
            'clientes' => $this->clientes
        ]);
    }

    // Método para abrir detalle de una promo
    public function verDetalle($itemId)
    {
        $this->promoSeleccionada = ItemPromo::with(['cliente', 'promo'])->find($itemId);

        if ($this->promoSeleccionada) {
            $this->modalVisible = true;
        }
    }

    // Método para cerrar el modal
    public function cerrarModal()
    {
        $this->modalVisible = false;
        $this->promoSeleccionada = null;
    }
}
