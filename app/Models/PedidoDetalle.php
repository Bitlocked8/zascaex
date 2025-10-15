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

    // Relación con Pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    // Relación con Reposicion (lote físico)
    public function reposicion()
    {
        return $this->belongsTo(Reposicion::class);
    }

    // Relación con Existencia (producto)
    public function existencia()
    {
        return $this->belongsTo(Existencia::class);
    }
}
