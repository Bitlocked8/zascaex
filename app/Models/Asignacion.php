<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asignacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'fechaInicio',
        'fechaFinal',
        'estado',
        'coche_id',
        'personal_id',
    ];

    /**
     * Relación: Una asignación pertenece a un coche.
     */
    public function coche()
    {
        return $this->belongsTo(Coche::class);
    }

    /**
     * Relación: Una asignación pertenece a un personal.
     */
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}

