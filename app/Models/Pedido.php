<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'solicitud_pedido_id',
        'cliente_id',
        'personal_id',
        'estado_pedido',
        'observaciones',
        'fecha_pedido',
    ];

    public function solicitudPedido()
    {
        return $this->belongsTo(SolicitudPedido::class, 'solicitud_pedido_id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class);
    }

    public function pagoPedidos()
    {
        return $this->hasMany(PagoPedido::class, 'pedido_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }


    public function distribuciones()
    {
        return $this->belongsToMany(Distribucion::class, 'distribucion_pedidos')
            ->withTimestamps();
    }

    public function adornados()
    {
        return $this->hasMany(Adornado::class);
    }
}
