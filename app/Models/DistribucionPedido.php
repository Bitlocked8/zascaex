<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistribucionPedido extends Model
{
    use HasFactory;

    protected $table = 'distribucion_pedidos';

    protected $fillable = [
        'distribucion_id',
        'pedido_id',
    ];

    public function distribucion()
    {
        return $this->belongsTo(Distribucion::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
