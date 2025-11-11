<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reposicion extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'fecha',
        'cantidad',
        'estado_revision',
        'existencia_id',
        'cantidad_inicial',
        'personal_id',
        'proveedor_id',
        'observaciones'
    ];


    public function existencia()
    {
        return $this->belongsTo(Existencia::class);
    }


    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }


    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function comprobantes()
    {
        return $this->hasMany(ComprobantePago::class);
    }

    public function asignados()
    {
        return $this->belongsToMany(Asignado::class, 'asignado_reposicions')
            ->withPivot('cantidad', 'cantidad_original', 'existencia_id')
            ->withTimestamps();
    }


    public function traspasosOrigen()
    {
        return $this->belongsToMany(Traspaso::class, 'reposicion_traspasos')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    public function traspasosDestino()
    {
        return $this->hasMany(Traspaso::class, 'reposicion_destino_id');
    }

    public function soplados()
    {
        return $this->hasMany(Soplado::class);
    }
    public function llenados()
    {
        return $this->hasMany(Llenado::class);
    }

    public function adornados()
    {
        return $this->belongsToMany(Adornado::class, 'adornado_reposicions')
            ->withPivot('cantidad_usada')
            ->withTimestamps();
    }

}
