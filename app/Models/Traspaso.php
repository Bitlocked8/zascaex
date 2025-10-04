<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traspaso extends Model
{


    protected $fillable = [
        'reposicion_origen_id',
        'reposicion_destino_id',
        'personal_id',
        'cantidad',
        'fecha_traspaso',
        'observaciones',
        'codigo',
    ];

    // Relación con reposiciones
    public function reposicionOrigen()
    {
        return $this->belongsTo(Reposicion::class, 'reposicion_origen_id');
    }

    public function reposicionDestino()
    {
        return $this->belongsTo(Reposicion::class, 'reposicion_destino_id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
