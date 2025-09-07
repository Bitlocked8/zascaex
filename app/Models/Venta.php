<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'fechaPedido',
        'fechaEntrega',
        'fechaMaxima',
        'sucursal_id', 
        'cliente_id', 
        'personal_id',
        'personalEntrega_id',
        'estadoPedido',
        'estadoPago',
    ];

    /**
     * Relación: Una venta pertenece a un cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación: Una venta pertenece a un personal.
     */
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
   public function personalEntrega()
{
    return $this->belongsTo(Personal::class, 'personalEntrega_id');
}


    /**
     * Relación: Una venta pertenece a una distribución.
     */
    public function distribucion()
    {
        return $this->belongsTo(Distribucion::class);
    }
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relación: Una venta tiene uno o varios pagos.
     */
    public function pagos()
    {
        return $this->hasMany(Pagoventa::class);
    }
    /**
     * Relación: Una venta tiene uno o varios itemventas.
     */
    public function itemventas()
    {
        return $this->hasMany(Itemventa::class);
    }
}
