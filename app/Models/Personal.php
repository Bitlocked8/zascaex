<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Personal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombres',
        'apellidos',
        'direccion',
        'celular',
        'estado',
        'user_id',
    ];

    /**
     * Relación con el modelo Usuario (nullable).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación 1:N con Asignacion.
     */
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }

    /**
     * Relación 1:N con Venta.
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    /**
     * Relación 1:N con Enbotellado.
     */
    public function enbotellados()
    {
        return $this->hasMany(Embotellado::class);
    }

    /**
     * Relación 1:N con Elaboracion.
     */
    public function elaboraciones()
    {
        return $this->hasMany(Elaboracion::class);
    }

    /**
     * Relación 1:N con Compra.
     */
    public function compras()
    {
        return $this->hasMany(Compra::class);
    }
    public function trabajos()
    {
        return $this->hasMany(Trabajo::class);
    }
}
