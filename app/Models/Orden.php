<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    /** @use HasFactory<\Database\Factories\OrdenFactory> */
    use HasFactory;
    protected $table = 'ordens';
    protected $fillable = [
        'fecha',
        'fecha_fin',
        'detalle',
        'cantidad_total',
        'cantidad_preparada',
        'estado',
    ];
}
