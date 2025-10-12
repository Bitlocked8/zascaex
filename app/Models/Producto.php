<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagen',
        'unidad',
        'descripcion',       // nombre del producto
        'tipoContenido',     // agua normal, con gas, etc.
        'tipoProducto',      // botella, botellón, etc.
        'capacidad',         // capacidad numérica
        'precioReferencia',  // precio de referencia
        'paquete',           // 10 unidades, etc.
        'observaciones',     // comentarios u otros datos
        'estado',
        'tipo',              // Plástico, Vidrio, etc.
    ];

    /**
     * Relación: Un producto pertenece a un enbotellado.
     */
    public function enbotellado()
    {
        return $this->belongsTo(Embotellado::class);
    }

    /**
     * Relación 1:N con Stock.
     */
    public function existencias(): MorphMany
    {
        return $this->morphMany(Existencia::class, 'existenciable');
    }
}
