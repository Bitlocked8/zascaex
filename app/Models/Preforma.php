<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Preforma extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagen',
        'detalle',
        'insumo',
        'gramaje',
        'cuello',
        'descripcion',
        'capacidad',
        'color',
        'estado',
        'observaciones',
    ];


    /**
     * Relación 1:N con Elaboracion.
     */
    public function elaboraciones()
    {
        return $this->hasMany(Elaboracion::class);
    }

    /**
     * Relación 1:N con ItemCompra.
     */
    public function itemCompras()
    {
        return $this->hasMany(ItemCompra::class);
    }

    /**
     * Relación polimórfica con Existencia.
     */
    public function existencias(): MorphMany
    {
        return $this->morphMany(Existencia::class, 'existenciable');
    }
}
