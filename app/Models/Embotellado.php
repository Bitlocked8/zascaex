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
        'fecha_embotellado',
        'observaciones',
        'mermaTapa',
        'mermaBase',
    ];

    public function existenciaBase() {
        return $this->belongsTo(Existencia::class, 'existencia_base_id');
    }

    public function existenciaTapa() {
        return $this->belongsTo(Existencia::class, 'existencia_tapa_id');
    }

    public function existenciaProducto() {
        return $this->belongsTo(Existencia::class, 'existencia_producto_id');
    }

    public function personal() {
        return $this->belongsTo(Personal::class);
    }
}

