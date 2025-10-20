<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distribucion extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'fecha_asignacion',
        'fecha_entrega',
        'estado',
        'observaciones',
        'coche_id',
        'personal_id',
    ];

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'distribucion_pedidos')
            ->withTimestamps();
    }

    public function coche()
    {
        return $this->belongsTo(Coche::class);
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
