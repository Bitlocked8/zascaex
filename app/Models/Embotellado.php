<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Embotellado extends Model
{
    use HasFactory;

    protected $fillable = [
        'existencia_base_id',
        'existencia_tapa_id',
        'existencia_producto_id',
        'personal_id',
        'cantidad_base_usada',
        'cantidad_tapa_usada',
        'cantidad_generada',
        'mermaTapa',
        'mermaBase',
        'residuo_base',
        'residuo_tapa',
        'estado_residuo_base',  // 0 = espera lote, 1 = asignado
        'estado_residuo_tapa',  // 0 = espera lote, 1 = asignado
        'fecha_embotellado',
        'fecha_embotellado_final',
        'observaciones',
        'codigo',
        'estado',
    ];

    // Relaciones con existencias
    public function existenciaBase() {
        return $this->belongsTo(Existencia::class, 'existencia_base_id');
    }

    public function existenciaTapa() {
        return $this->belongsTo(Existencia::class, 'existencia_tapa_id');
    }

    public function existenciaProducto() {
        return $this->belongsTo(Existencia::class, 'existencia_producto_id');
    }

    // Relación con personal
    public function personal() {
        return $this->belongsTo(Personal::class);
    }
}
