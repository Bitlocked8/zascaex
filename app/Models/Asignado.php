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
        return $this->belongsToMany(Reposicion::class, 'asignado_reposicions') // asegúrate que coincide con la migración
            ->withPivot('cantidad', 'existencia_id') // agrega existencia_id si lo necesitas en pivot
            ->withTimestamps();
    }

    public function soplados()
    {
        return $this->hasMany(Soplado::class);
    }

}
