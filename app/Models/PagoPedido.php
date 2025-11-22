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
        'sucursal_pago_id',
        'metodo',
        'referencia',
        'fecha_pago',
        'imagen_comprobante',
        'estado',
        'observaciones',
        'codigo_pago',
    ];


    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function sucursalPago()
    {
        return $this->belongsTo(SucursalPago::class, 'sucursal_pago_id');
    }
}
