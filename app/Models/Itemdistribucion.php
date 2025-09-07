<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Itemdistribucion extends Model
{
    use HasFactory;

    protected $fillable = [
        'cantidadNuevo',
        'cantidadUsados',
        'stock_id',
        'distribucion_id',
    ];

    /**
     * Relación: Un item distribución pertenece a un stock.
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Relación: Un item distribución pertenece a una distribución.
     */
    public function distribucion()
    {
        return $this->belongsTo(Distribucion::class);
    }
}
