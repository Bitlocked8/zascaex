<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'codigo',
        'cliente_id',
        'personal_id',
        'estado_pedido',
        'observaciones',
        'fecha_pedido',
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class);
    }

    public function pagoPedidos()
    {
        return $this->hasMany(PagoPedido::class, 'pedido_id');
    }

    public function distribuciones()
    {
        return $this->belongsToMany(Distribucion::class, 'distribucion_pedidos')
            ->withTimestamps();
    }
}
