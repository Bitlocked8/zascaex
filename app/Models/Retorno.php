<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Retorno extends Model
{
    use HasFactory;

    protected $fillable = [
        'botellonesNuevos',
        'llenos',
        'vacios',
        'reportado',
        'desechar',
        'recuperados',
        'encargado',
        'observaciones',
        'distribucion_id',
    ];

    /**
     * Relación: Un retorno pertenece a una distribución.
     */
    public function distribucion()
    {
        return $this->belongsTo(Distribucion::class);
    }
}

