<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traspaso extends Model
{
    protected $fillable = [
        'reposicion_destino_id',
        'personal_id',
        'fecha_traspaso',
        'cantidad',
        'observaciones',
        'codigo',
    ];

    // Relación con múltiples reposiciones de origen
    public function reposicionesOrigen()
    {
        return $this->belongsToMany(Reposicion::class, 'reposicion_traspasos')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    // Relación con reposición destino
    public function reposicionDestino()
    {
        return $this->belongsTo(Reposicion::class, 'reposicion_destino_id');
    }

    // Relación con personal
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
