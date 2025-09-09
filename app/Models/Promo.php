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
        'cliente_id',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];

    // Solo esto hace que las fechas sean objetos Carbon automáticamente
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}

