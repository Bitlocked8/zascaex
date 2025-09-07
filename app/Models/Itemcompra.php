<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Itemcompra extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'cantidad',
        'precio',
        'existencia_id',
        'compra_id',
    ];

    /**
     * Relación: Un ItemCompra pertenece a una Existencia.
     */
    public function existencia()
    {
        return $this->belongsTo(Existencia::class);
    }

    /**
     * Relación: Un ItemCompra pertenece a una Compra.
     */
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }
    
}

