<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trabajo extends Model
{
    use HasFactory;

    protected $fillable = [
        'fechaInicio',
        'fechaFinal',
        'estado',
        'sucursal_id',
        'personal_id',
    ];

    /**
     * Relación: Un trabajo pertenece a una sucursal.
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relación: Un trabajo pertenece a un personal.
     */
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
