<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Existencia extends Model
{
    /** @use HasFactory<\Database\Factories\ExistenciaFactory> */
    use HasFactory;

    protected $fillable = [
        'existenciable_id',
        'existenciable_type',
        'cantidadMinima',
        'cantidad',
        'sucursal_id',
    ];

    /**
     * Relación polimórfica para asociar la existencia con diferentes tipos de artículos.
     */
    public function existenciable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relación con la Sucursal.
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function elaboracion()
    {
        return $this->belongsTo(Elaboracion::class);
    }
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
