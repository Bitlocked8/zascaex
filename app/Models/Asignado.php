<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignado extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'personal_id',
        'cantidad',
        'cantidad_original',
        'fecha',
        'motivo',
        'observaciones'
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    public function reposiciones()
    {
        return $this->belongsToMany(Reposicion::class, 'asignado_reposicions')
            ->withPivot('cantidad', 'cantidad_original', 'existencia_id')
            ->withTimestamps();
    }


    public function soplados()
    {
        return $this->hasMany(Soplado::class);
    }

    public function llenados()
    {
        return $this->hasMany(Llenado::class);
    }

    public function asignadoReposicions()
    {
        return $this->hasMany(AsignadoReposicion::class);
    }

    public function traspasos()
{
    return $this->hasMany(Traspaso::class, 'asignacion_id');
}


}
