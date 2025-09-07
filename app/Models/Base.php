<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Base extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagen',
        'unidad',
        'capacidad',
        'descripcion',
        'estado',
        'observaciones',
        'preforma_id',
    ];

    /**
     * Relación 1:1 con Elaboracion.
     */
    
    public function existencias()
    {
        return $this->morphMany(Existencia::class, 'existenciable');
    }
    public function preforma()
    {
        return $this->belongsTo(Preforma::class, 'preforma_id');
    }
}

