<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoPedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'sucursal_pago_id',
        'monto',
        'metodo',
        'estado',
        'referencia',
        'codigo_factura',
        'fecha',
        'archivo_factura',
        'archivo_comprobante',
        'observaciones',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
    public function sucursalPago()
    {
        return $this->belongsTo(SucursalPago::class);
    }
    public function detalles()
    {
        return $this->hasMany(PagoDetalle::class);
    }
}
