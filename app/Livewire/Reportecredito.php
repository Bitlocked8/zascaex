<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Venta;
use App\Models\Cliente;

class Reportecredito extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'tailwind';

    public function render()
    {
        // Fetch clients with pending sales (estadoPago = 0)
        $clientes = Cliente::whereHas('ventas', function ($query) {
            $query->where('estadoPago', 0)
                  ->when($this->search, function ($subQuery) {
                      $subQuery->whereHas('cliente', function ($q) {
                          $q->where('nombres', 'like', '%' . $this->search . '%')
                            ->orWhere('apellidos', 'like', '%' . $this->search . '%');
                      });
                  });
        })
        ->with(['ventas' => function ($query) {
            $query->where('estadoPago', 0)
                  ->with(['sucursal', 'personal', 'personalEntrega', 'pagos', 'itemventas'])
                  ->orderBy('fechaMaxima', 'asc'); // Sort sales by fechaMaxima
        }])
        ->get()
        ->map(function ($cliente) {
            // Calculate total pending amount for each client
            $cliente->totalPendiente = $cliente->ventas->sum(function ($venta) {
                $totalVenta = $venta->itemventas->sum(function ($item) {
                    return $item->cantidad * $item->precio;
                });
                $totalPagado = $venta->pagos->sum('monto');
                return $totalVenta - $totalPagado;
            });
            return $cliente; 
        });

        return view('livewire.reportecredito', compact('clientes'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
