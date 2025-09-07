<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'imagen',
        'tipoContenido',
        'tipoProducto',
        'capacidad',
        'unidad',
        'precioReferencia',
        'precioReferencia2',
        'precioReferencia3',
        'observaciones',
        'estado',
        'paquete',
        'base_id',
        'tapa_id',
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
    public function base()
    {
        return $this->belongsTo(Base::class, 'base_id');
    }
}
