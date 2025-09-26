<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprobantePago extends Model
{
    use HasFactory;

    protected $fillable = [
        'reposicion_id',
        'codigo',
        'monto',
        'fecha_pago',
        'observaciones',
    ];

    /**
     * Relación: Un comprobante pertenece a una reposición.
     */
    public function reposicion()
    {
        return $this->belongsTo(Reposicion::class);
    }
}
