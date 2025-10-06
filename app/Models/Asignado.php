<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignado extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'existencia_id',
        'personal_id',
        'cantidad',
        'cantidad_original',
        'fecha',
        'motivo',
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

    public function reposiciones()
    {
        return $this->belongsToMany(Reposicion::class, 'asignado_reposicions')
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
