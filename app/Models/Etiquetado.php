<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Etiquetado extends Model
{
    /** @use HasFactory<\Database\Factories\EtiquetadoFactory> */
    use HasFactory;
    protected $fillable = [
        'existencia_producto_id',
        'existencia_etiqueta_id',
        'existencia_stock_id',
        'personal_id',
        'cantidad_producto_usado',
        'cantidad_etiqueta_usada',
        'cantidad_generada',
        'fecha_etiquetado',
        'observaciones',
        'mermaProducto',
        'mermaEtiqueta',
    ];

    public function existenciaProducto(): BelongsTo {
        return $this->belongsTo(Existencia::class, 'existencia_producto_id');
    }

    public function existenciaEtiqueta() {
        return $this->belongsTo(Existencia::class, 'existencia_etiqueta_id');
    }

    public function existenciaStock() {
        return $this->belongsTo(Existencia::class, 'existencia_stock_id');
    }

    public function personal() {
        return $this->belongsTo(Personal::class);
    }
}
