<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tapa extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagen',
        'color',
        'tipo',
        'estado',
        'descripcion',
    ];

    /**
     * Relación: Una tapa puede ser utilizada en múltiples bases.
     */
    // public function bases()
    // {
    //     return $this->hasMany(Base::class);
    // }
    public function existencias()
    {
        return $this->morphMany(Existencia::class, 'existenciable');
    }
}
