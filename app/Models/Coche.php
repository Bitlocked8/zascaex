<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coche extends Model
{
    use HasFactory;

    protected $fillable = [
        'marca',
        'modelo',
        'anio',
        'color',
        'placa',
        'estado',
    ];

    /**
     * Relación 1:N con Asignacion.
     */
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }
}

