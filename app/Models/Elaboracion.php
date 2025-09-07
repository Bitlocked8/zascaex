<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Elaboracion extends Model
{
    use HasFactory;

    protected $fillable = [
        'existencia_entrada_id',
        'existencia_salida_id',
        'personal_id',
        'cantidad_entrada',
        'cantidad_salida',
        'fecha_elaboracion',
        'observaciones',
    ];

    public function existenciaEntrada(): BelongsTo {
        return $this->belongsTo(Existencia::class, 'existencia_entrada_id');
    }

    public function existenciaSalida() {
        return $this->belongsTo(Existencia::class, 'existencia_salida_id');
    }

    public function personal() {
        return $this->belongsTo(Personal::class);
    }
}

