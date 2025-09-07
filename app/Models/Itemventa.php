<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Itemventa extends Model
{
    use HasFactory;

    protected $fillable = [
        'cantidad',
        'precio',
        'existencia_id',
        'venta_id',
        'estado',
    ];

    /**
     * Relación: Un ItemVenta pertenece a una Existencia (Stock).
     */
    public function existencia()
    {
        return $this->belongsTo(Existencia::class);
    }

    /**
     * Relación: Un ItemVenta pertenece a una Venta.
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
