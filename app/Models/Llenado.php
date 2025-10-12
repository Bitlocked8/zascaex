<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Llenado extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'asignado_base_id',
        'asignado_tapa_id',
        'existencia_id',
        'personal_id',
        'reposicion_id',
        'cantidad',
        'merma_base',
        'merma_tapa',
        'estado',
        'observaciones',
        'fecha',
    ];
    public function asignadoBase()
    {
        return $this->belongsTo(Asignado::class, 'asignado_base_id');
    }
    public function asignadoTapa()
    {
        return $this->belongsTo(Asignado::class, 'asignado_tapa_id');
    }
    public function existencia()
    {
        return $this->belongsTo(Existencia::class);
    }
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
    public function reposicion()
    {
        return $this->belongsTo(Reposicion::class);
    }
}
