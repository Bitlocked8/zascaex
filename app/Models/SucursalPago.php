<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SucursalPago extends Model
{
    use HasFactory;

    protected $fillable = [
        'sucursal_id',
        'nombre',
        'tipo',
        'numero_cuenta',
        'titular',
        'imagen_qr',
        'estado',
    ];

    // Relación con sucursal
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
