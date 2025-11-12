<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Preforma extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagen',
        'detalle',
        'insumo',
        'gramaje',
        'cuello',
        'descripcion',
        'capacidad',
        'color',
        'estado',
        'observaciones',
    ];


    public function existencias(): MorphMany
    {
        return $this->morphMany(Existencia::class, 'existenciable');
    }
}
