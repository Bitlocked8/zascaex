<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traspaso extends Model
{
    protected $fillable = [
        'existencia_origen_id',
        'existencia_destino_id',
        'personal_id',
        'cantidad',
        'fecha_traspaso',
        'observaciones',
    ];

    public function existenciaOrigen()
    {
        return $this->belongsTo(Existencia::class, 'existencia_origen_id');
    }

    public function existenciaDestino()
    {
        return $this->belongsTo(Existencia::class, 'existencia_destino_id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
