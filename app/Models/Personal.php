<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Personal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombres',
        'apellidos',
        'direccion',
        'celular',
        'estado',
        'user_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
    public function enbotellados()
    {
        return $this->hasMany(Embotellado::class);
    }
    public function elaboraciones()
    {
        return $this->hasMany(Elaboracion::class);
    }
    public function compras()
    {
        return $this->hasMany(Compra::class);
    }
    public function trabajos()
    {
        return $this->hasMany(Trabajo::class);
    }

    public function reposiciones()
    {
        return $this->hasMany(Reposicion::class);
    }
}
