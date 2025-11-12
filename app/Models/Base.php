<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Base extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagen',
        'capacidad',
        'descripcion',
        'estado',
        'observaciones',
        'tipo',
        'compatibilidad',
    ];

    /**
     * Relación polimórfica con Existencia.
     */
    public function existencias()
    {
        return $this->morphMany(Existencia::class, 'existenciable');
    }
}
