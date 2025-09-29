<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignado extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'codigo',
        'existencia_id',
        'personal_id',
        'cantidad',
        'fecha',
        'motivo',
        'observaciones'
    ];

    // Relación con existencia
    public function existencia()
    {
        return $this->belongsTo(Existencia::class);
    }

    // Relación con personal
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

   
}
