<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soplado extends Model
{
    /** @use HasFactory<\Database\Factories\SopladoFactory> */
    use HasFactory;
    protected $fillable = [
        'codigo',
        'asignado_id',
        'existencia_id',
        'reposicion_id',
        'personal_id',
        'cantidad',
        'merma',
        'estado',
        'observaciones',
        'fecha',
    ];

    public function asignado()
    {
        return $this->belongsTo(Asignado::class);
    }

    public function reposicion()
    {
        return $this->belongsTo(Reposicion::class);
    }

    public function existencia()
    {
        return $this->belongsTo(Existencia::class);
    }
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
