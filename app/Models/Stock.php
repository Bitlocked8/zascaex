<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'fechaElaboracion',
        'fechaVencimiento',
        'observaciones',
        'etiqueta_id',
        'producto_id',
        // 'sucursal_id', 
    ];

    /**
     * Relación: Un stock pertenece a un producto.
     */
    // public function producto()
    // {
    //     return $this->belongsTo(Producto::class);
    // }

    public function existencias()
    {
        return $this->morphMany(Existencia::class, 'existenciable');
    }
    /**
     * Relación: Un stock pertenece a una sucursal.
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relación 1:N con Retorno.
     */
    public function retornos()
    {
        return $this->hasMany(Retorno::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
    public function etiqueta()
    {
        return $this->belongsTo(Etiqueta::class, 'etiqueta_id');
    }
    public function distribucion()
    {
        return $this->belongsTo(Distribucion::class);
    }
}
