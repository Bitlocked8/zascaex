<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'observaciones',
        'proveedor_id',
        'personal_id',
    ];

    /**
     * Relación: Una compra pertenece a un proveedor.
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Relación: Una compra pertenece a un personal.
     */
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    /**
     * Relación 1:N con ItemCompra.
     */
    public function itemcompras()
    {
        return $this->hasMany(ItemCompra::class);
    }
}
        