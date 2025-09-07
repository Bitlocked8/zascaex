<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venta;

class Pedidos extends Component
{
    public function render()
    {
        // Trae todas las ventas con cliente, productos y pagos
        $ventas = Venta::with([
            'cliente',
            'itemventas.existencia.existenciable',
            'pagos' // Relación con pagoventas
        ])->latest()->get();

        return view('livewire.pedidos', [
            'ventas' => $ventas,
        ]);
    }
}
