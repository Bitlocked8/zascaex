<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoPedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'monto',
        'metodo',
        'referencia',
        'fecha_pago',
        'imagen_comprobante',
        'estado',
        'observaciones',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
