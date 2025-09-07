<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Etiqueta extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagen',
        'capacidad',
        'estado',
        'unidad',
        'descripcion',
        'cliente_id',
        
    ];

    /**
     * Relación: Una etiqueta puede estar asociada a un cliente (opcional).
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación 1:N con Productos (si aplica).
     */
    // public function productos()
    // {
    //     return $this->hasMany(Producto::class);
    // }
    public function existencias(): MorphMany
    {
        return $this->morphMany(Existencia::class, 'existenciable');
    }
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
    
}
