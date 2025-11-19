<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudPedidoDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'solicitud_pedido_id',
        'descripcion',
        'cantidad',
        'paquete',
        'precio_unitario',
        'total',
        'tapa_descripcion',
        'tapa_imagen',
        'etiqueta_descripcion',
        'tipo_contenido',
        'etiqueta_imagen',
    ];

    public function solicitudPedido()
    {
        return $this->belongsTo(SolicitudPedido::class);
    }
}
