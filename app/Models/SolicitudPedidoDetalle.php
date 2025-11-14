<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudPedidoDetalle extends Model
{
    use HasFactory;

    protected $fillable = ['solicitud_pedido_id', 'producto_id', 'cantidad', 'precio'];

    public function solicitudPedido()
    {
        return $this->belongsTo(SolicitudPedido::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
