<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sucursal as ModelSucursal;
use App\Models\Empresa;
use Livewire\WithFileUploads;

class Sucursal extends Component
{

    use WithFileUploads;
    public $search = '';
    public $modal = false;
    public $detalleModal = false;
    public $accion = 'create';
    public $sucursalId = null;

    public $nombre = '';
    public $direccion = '';
    public $telefono = '';
    public $zona = '';
    public $empresa_id = '';
    public $modalPagosSucursal = false;
    public $sucursalParaPago; // id de la sucursal seleccionada
    public $pagosSucursal = []; // array de pagos dinámicos

    public $sucursalSeleccionada = null;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
        'telefono' => 'required|string|max:15',
        'zona' => 'nullable|string|max:255',
        'empresa_id' => 'required|exists:empresas,id',
    ];

    public function render()
    {
        $sucursales = ModelSucursal::with('empresa')
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('direccion', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->get();

        $empresas = Empresa::all();

        return view('livewire.sucursal', compact('sucursales', 'empresas'));
    }

    public function abrirModal($accion)
    {
        $this->reset(['nombre', 'direccion', 'telefono', 'zona', 'empresa_id', 'sucursalId']);
        $this->accion = $accion;
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function editarSucursal($id)
    {
        $sucursal = ModelSucursal::findOrFail($id);
        $this->sucursalId = $sucursal->id;
        $this->nombre = $sucursal->nombre;
        $this->direccion = $sucursal->direccion;
        $this->telefono = $sucursal->telefono;
        $this->zona = $sucursal->zona;
        $this->empresa_id = $sucursal->empresa_id;
        $this->accion = 'edit';
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function verDetalle($id)
    {
        $this->sucursalSeleccionada = ModelSucursal::with('empresa')->findOrFail($id);
        $this->modal = false;
        $this->detalleModal = true;
    }

    public function guardarSucursal()
    {
        $this->validate();

        if ($this->accion === 'edit' && $this->sucursalId) {
            $sucursal = ModelSucursal::findOrFail($this->sucursalId);
            $sucursal->update([
                'nombre' => $this->nombre,
                'direccion' => $this->direccion,
                'telefono' => $this->telefono,
                'zona' => $this->zona,
                'empresa_id' => $this->empresa_id,
            ]);
        } else {
            ModelSucursal::create([
                'nombre' => $this->nombre,
                'direccion' => $this->direccion,
                'telefono' => $this->telefono,
                'zona' => $this->zona,
                'empresa_id' => $this->empresa_id,
            ]);
        }

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->detalleModal = false;
        $this->reset(['nombre', 'direccion', 'telefono', 'zona', 'empresa_id', 'sucursalId', 'sucursalSeleccionada']);
        $this->resetErrorBag();
    }

    public function abrirModalPagosSucursal($sucursal_id)
    {
        $this->sucursalParaPago = $sucursal_id;

        $this->pagosSucursal = \App\Models\SucursalPago::where('sucursal_id', $sucursal_id)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'tipo' => $p->tipo,
                'numero_cuenta' => $p->numero_cuenta,
                'titular' => $p->titular,
                'imagen_qr' => $p->imagen_qr,
                'estado' => $p->estado,
            ])->toArray();

        $this->modalPagosSucursal = true;
    }

    public function eliminarPagoSucursal($index)
    {
        $pago = $this->pagosSucursal[$index] ?? null;
        if ($pago && isset($pago['id']) && $pago['id']) {
            \App\Models\SucursalPago::find($pago['id'])?->delete();
        }
        unset($this->pagosSucursal[$index]);
        $this->pagosSucursal = array_values($this->pagosSucursal);
    }

    public function agregarPagoSucursal()
    {
        $this->pagosSucursal[] = [
            'id' => null,
            'codigo' => 'PAGO-' . now()->format('Ymd') . '-' . str_pad(count($this->pagosSucursal) + 1, 3, '0', STR_PAD_LEFT),
            'fecha' => now()->format('Y-m-d'),
            'monto' => null,
            'observaciones' => null,
            'imagen_qr' => null, // campo de la tabla sucursal_pagos
        ];
    }


    public function guardarPagosSucursal()
    {
        foreach ($this->pagosSucursal as $index => $pago) {

            // Validar que nombre no esté vacío
            if (empty($pago['nombre'])) {
                $this->addError("pagosSucursal.$index.nombre", "El nombre es obligatorio");
                continue; // No guardar este pago si falla
            }

            $imagenPath = $pago['imagen_qr'] ?? null;
            if ($imagenPath instanceof \Illuminate\Http\UploadedFile) {
                $imagenPath = $pago['imagen_qr']->store('pagos_sucursal', 'public');
            }

            \App\Models\SucursalPago::updateOrCreate(
                ['id' => $pago['id'] ?? 0],
                [
                    'sucursal_id' => $this->sucursalParaPago,
                    'nombre' => $pago['nombre'],
                    'tipo' => $pago['tipo'] ?? null,
                    'numero_cuenta' => $pago['numero_cuenta'] ?? null,
                    'titular' => $pago['titular'] ?? null,
                    'imagen_qr' => $imagenPath,
                    'estado' => $pago['estado'] ?? true,
                ]
            );
        }

        // Solo resetear si no hay errores
        if (! $this->getErrorBag()->has('pagosSucursal')) {
            $this->reset(['pagosSucursal']);
            $this->modalPagosSucursal = false;
        }
    }
}
