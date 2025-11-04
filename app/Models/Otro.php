<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Otro extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagen',
        'unidad',
        'descripcion',
        'tipoContenido',
        'tipoProducto',
        'capacidad',
        'precioReferencia',
        'paquete',
        'observaciones',
        'estado',
        'tipo',
    ];

    /**
     * Relación 1:N polimórfica con Existencia.
     */
    public function existencias(): MorphMany
    {
        return $this->morphMany(Existencia::class, 'existenciable');
    }
}
