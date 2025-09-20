<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reposicion extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'cantidad',
        'precio_unitario',
        'imagen',
        'existencia_id',
        'personal_id',
        'proveedor_id',
        'observaciones',
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
}
