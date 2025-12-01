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

   
    public function existencias()
    {
        return $this->morphMany(Existencia::class, 'existenciable');
    }
}
