<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'slogan',
        'mision',
        'vision',
        'nroContacto',
        'facebook',
        'instagram',
        'tiktok',
    ];

    /**
     * Relación 1:N con Sucursal.
     */
    public function sucursales()
    {
        return $this->hasMany(Sucursal::class);
    }
}
