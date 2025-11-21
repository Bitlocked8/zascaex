<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudPedido extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'codigo', 'observaciones', 'estado', 'metodo_pago'];

    public function detalles()
    {
        return $this->hasMany(SolicitudPedidoDetalle::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function pedido()
    {
        return $this->hasOne(Pedido::class, 'solicitud_pedido_id');
    }
}
