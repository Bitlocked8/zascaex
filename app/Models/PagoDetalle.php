<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_detalle_id',
        'precio_base',
        'precio_aplicado',
        'subtotal',
    ];

    public function pedidoDetalle()
    {
        return $this->belongsTo(PedidoDetalle::class);
    }
}
