<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignadoReposicion extends Model
{
    use HasFactory;

    // Nombre de la tabla (Laravel intentará pluralizar raro si no lo pones)
    protected $table = 'asignado_reposicions';

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'asignado_id',
        'reposicion_id',
        'cantidad',
    ];

    // Relaciones
    public function asignado()
    {
        return $this->belongsTo(Asignado::class);
    }

    public function reposicion()
    {
        return $this->belongsTo(Reposicion::class);
    }
}
