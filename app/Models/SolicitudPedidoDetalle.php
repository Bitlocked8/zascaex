<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudPedidoDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'solicitud_pedido_id',
        'producto_id',
        'otro_id',
        'tapa_id',
        'etiqueta_id',
        'cantidad',
    ];

    public function solicitudPedido()
    {
        return $this->belongsTo(SolicitudPedido::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function otro()
    {
        return $this->belongsTo(Otro::class);
    }

    public function tapa()
    {
        return $this->belongsTo(Tapa::class);
    }

    public function etiqueta()
    {
        return $this->belongsTo(Etiqueta::class);
    }
}
