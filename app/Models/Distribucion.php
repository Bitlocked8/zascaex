<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distribucion extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'estado',
        'observaciones',
        'asignacion_id',
    ];

    /**
     * Relación: Una distribución pertenece a una asignación.
     */
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class);
    }

    /**
     * Relación 0:1 con Retorno.
     */
    public function retorno()
    {
        return $this->hasOne(Retorno::class);
    }
    public function stocks(): HasMany
    {
        return $this->hasMany(Retorno::class);
    }
    public function itemdistribucions(): HasMany
    {
        return $this->hasMany(Itemdistribucion::class);
    }
}
