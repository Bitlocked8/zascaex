<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pagoventa extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'codigo',
        'monto',
        'fechaPago',
        'observaciones',
        'venta_id',
    ];

    /**
     * Relación: Un pago pertenece a una venta.
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}

