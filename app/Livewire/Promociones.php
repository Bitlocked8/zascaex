<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ItemPromo;
use App\Models\Cliente;
use App\Models\Promo;

class Promociones extends Component
{
    public $itemPromos;

    public $modalConfirmar = false;
    public $codigoAEliminar = null;


    public $modal = false;
    public $editando = false;
    public $clientesSeleccionados = [];
    public $promosSeleccionadas = [];
    public $fechaAsignacion;
    public $codigo;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->itemPromos = ItemPromo::with(['cliente', 'promo'])->get();
    }

    public function render()
    {
        $clientes = Cliente::all();
        $promos   = Promo::all();

        return view('livewire.promociones', [
            'itemPromos' => $this->itemPromos,
            'clientes'   => $clientes,
            'promos'     => $promos,
        ]);
    }

    // Abrir modal crear
    public function abrirModal()
    {
        $this->reset(['clientesSeleccionados', 'promosSeleccionadas', 'fechaAsignacion', 'codigo']);
        $this->resetValidation();

        $this->codigo = 'PROMO-' . strtoupper(uniqid());
        $this->fechaAsignacion = now()->format('Y-m-d');
        $this->modal = true;
        $this->editando = false;
    }

    // Abrir modal editar
    public function editarLote($codigo)
    {
        $items = ItemPromo::where('codigo', $codigo)->get();

        if ($items->isNotEmpty()) {
            $this->codigo = $codigo;

            // Convertir fecha a formato Y-m-d para input date
            $this->fechaAsignacion = $items->first()->fecha_asignacion
                ? $items->first()->fecha_asignacion->format('Y-m-d')
                : now()->format('Y-m-d');

            // Convertir IDs a enteros (Livewire maneja los valores de checkbox como enteros)
            $this->clientesSeleccionados = $items->pluck('cliente_id')
                ->unique()
                ->map(fn($id) => (int)$id)
                ->toArray();

            $this->promosSeleccionadas = $items->pluck('promo_id')
                ->unique()
                ->map(fn($id) => (int)$id)
                ->toArray();

            $this->modal = true;
            $this->editando = true;
        }
    }

    // Cerrar modal
    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['clientesSeleccionados', 'promosSeleccionadas', 'fechaAsignacion', 'codigo']);
        $this->resetValidation();
    }

    // Guardar lote nuevo
    public function guardarLote()
    {
        $this->validate([
            'clientesSeleccionados' => 'required|array|min:1',
            'promosSeleccionadas'   => 'required|array|min:1',
            'fechaAsignacion'       => 'required|date',
        ]);

        foreach ($this->clientesSeleccionados as $clienteId) {
            foreach ($this->promosSeleccionadas as $promoId) {
                // Evitar duplicados
                ItemPromo::firstOrCreate(
                    [
                        'cliente_id' => $clienteId,
                        'promo_id'   => $promoId,
                        'codigo'     => $this->codigo,
                    ],
                    [
                        'fecha_asignacion' => $this->fechaAsignacion,
                        'estado' => 'activo'
                    ]
                );
            }
        }

        $this->itemPromos = ItemPromo::with(['cliente', 'promo'])->get();
        $this->cerrarModal();
        $this->dispatch('refreshComponent');
    }

    public function actualizarLote()
    {
        $this->validate([
            'clientesSeleccionados' => 'required|array|min:1',
            'promosSeleccionadas'   => 'required|array|min:1',
            'fechaAsignacion'       => 'required|date',
        ]);

        // Construir combinaciones válidas
        $combinacionesValidas = [];
        foreach ($this->clientesSeleccionados as $clienteId) {
            foreach ($this->promosSeleccionadas as $promoId) {
                $combinacionesValidas[] = [
                    'cliente_id' => $clienteId,
                    'promo_id' => $promoId,
                ];
            }
        }

        // Borrar solo las combinaciones que ya no existen
        ItemPromo::where('codigo', $this->codigo)->get()->each(function ($item) use ($combinacionesValidas) {
            $existe = collect($combinacionesValidas)->contains(function ($combo) use ($item) {
                return $combo['cliente_id'] == $item->cliente_id
                    && $combo['promo_id'] == $item->promo_id;
            });

            if (!$existe) {
                $item->delete();
            }
        });

        // Crear o actualizar las combinaciones seleccionadas
        foreach ($combinacionesValidas as $combo) {
            ItemPromo::updateOrCreate(
                [
                    'cliente_id' => $combo['cliente_id'],
                    'promo_id'   => $combo['promo_id'],
                    'codigo'     => $this->codigo,
                ],
                [
                    'fecha_asignacion' => $this->fechaAsignacion,
                    'estado' => 'activo'
                ]
            );
        }

        $this->itemPromos = ItemPromo::with(['cliente', 'promo'])->get();
        $this->cerrarModal();
        $this->dispatch('refreshComponent');
    }



    // Eliminar un lote completo
    public function confirmarEliminarLote($codigo)
    {
        $this->codigoAEliminar = $codigo;
        $this->modalConfirmar = true;
    }

    public function eliminarLoteConfirmado()
    {
        if ($this->codigoAEliminar) {
            // Aquí borras todos los itemPromos de ese código
            ItemPromo::where('codigo', $this->codigoAEliminar)->delete();
        }

        $this->modalConfirmar = false;
        $this->codigoAEliminar = null;

        // Refrescar lista
        $this->itemPromos = ItemPromo::with(['cliente', 'promo'])->get();
    }
    // Clientes
    public function agregarCliente($clienteId)
    {
        if (!in_array($clienteId, $this->clientesSeleccionados)) {
            $this->clientesSeleccionados[] = $clienteId;
        }
    }

    public function quitarCliente($clienteId)
    {
        $this->clientesSeleccionados = array_filter(
            $this->clientesSeleccionados,
            fn($id) => $id != $clienteId
        );
    }

    // Promociones
    public function agregarPromo($promoId)
    {
        if (!in_array($promoId, $this->promosSeleccionadas)) {
            $this->promosSeleccionadas[] = $promoId;
        }
    }

    public function quitarPromo($promoId)
    {
        $this->promosSeleccionadas = array_filter(
            $this->promosSeleccionadas,
            fn($id) => $id != $promoId
        );
    }
}
