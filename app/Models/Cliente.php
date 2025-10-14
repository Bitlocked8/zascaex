<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'empresa',
        'nitCi',
        'razonSocial',
        'direccion',
        'establecimiento',
        'disponible',
        'bot',
        'telefono',
        'celular',
        'correo',
        'ubicacion',
        'movil',
        'dias',
        'departamento_localidad',
        'latitud',
        'longitud',
        'foto',
        'estado',
        'verificado',
        'user_id',
        'categoria',
    ];


    /**
     * Relación 1:N con Venta.
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itemPromos()
    {
        return $this->hasMany(ItemPromo::class);
    }
}
