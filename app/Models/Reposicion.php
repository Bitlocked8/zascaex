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
        'existencia_id',
        'personal_id',
        'proveedor_id',
        'observaciones'
    ];

    /**
     * Relación: Una reposición pertenece a una existencia (lote/artículo).
     */
    public function existencia()
    {
        return $this->belongsTo(Existencia::class);
    }

    /**
     * Relación: Una reposición pertenece a un personal.
     */
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    /**
     * Relación: Una reposición puede tener un proveedor (opcional).
     */
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
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
