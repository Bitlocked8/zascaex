<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo_descuento',
        'valor_descuento',
        'uso_maximo',        // agregado
        'fecha_asignada',    // agregado
        'fecha_expiracion',  // agregado
        'activo',
    ];

    // Fechas y booleanos
    protected $casts = [

        'fecha_asignada'   => 'date',
        'fecha_expiracion' => 'date',
        'activo'           => 'boolean',
    ];

    public function itemPromos()
    {
        return $this->hasMany(ItemPromo::class);
    }
}
