<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'reposicion_id',
        'existencia_id',
        'cantidad',
    ];
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
    public function reposicion()
    {
        return $this->belongsTo(Reposicion::class);
    }
    public function existencia()
    {
        return $this->belongsTo(Existencia::class);
    }
    public function pagoDetalles()
    {
        return $this->hasMany(PagoDetalle::class, 'pedido_detalle_id');
    }
}
