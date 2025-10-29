<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Existencia extends Model
{

    use HasFactory;

    protected $fillable = [
        'existenciable_id',
        'existenciable_type',
        'cantidadMinima',
        'cantidad',
        'sucursal_id',
    ];

    public function existenciable(): MorphTo
    {
        return $this->morphTo();
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function elaboracion()
    {
        return $this->belongsTo(Elaboracion::class);
    }

    public function reposiciones()
    {
        return $this->hasMany(Reposicion::class);
    }

}
