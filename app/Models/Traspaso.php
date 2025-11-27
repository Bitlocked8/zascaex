<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Traspaso extends Model
{
    use HasFactory;
    protected $fillable = [
        'reposicion_destino_id',
        'asignacion_id',
        'personal_id',
        'fecha_traspaso',
        'cantidad',
        'observaciones',
        'codigo',
    ];

    public function asignacion()
    {
        return $this->belongsTo(Asignado::class, 'asignacion_id');
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

