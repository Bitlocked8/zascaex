<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trabajo extends Model
{
    use HasFactory;

    protected $fillable = [
        'fechaInicio',
        'fechaFinal',
        'estado',
        'sucursal_id',
        'personal_id',
        'labor_id',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    public function labor()
    {
        return $this->belongsTo(Labor::class);
    }
    public function trabajables()
    {
        return $this->hasMany(Trabajable::class);
    }
}
