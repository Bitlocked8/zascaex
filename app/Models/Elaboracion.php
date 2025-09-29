<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Trabajable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
        'merma',          
        'observaciones',
        'codigo', 
        'estado', 
    ];

    public function existenciaEntrada(): BelongsTo
    {
        return $this->belongsTo(Existencia::class, 'existencia_entrada_id');
    }

    public function existenciaSalida(): BelongsTo
    {
        return $this->belongsTo(Existencia::class, 'existencia_salida_id');
    }

    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class);
    }

    public function trabajables(): MorphMany
    {
        return $this->morphMany(Trabajable::class, 'trabajable');
    }
}
