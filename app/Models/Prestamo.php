<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prestamo extends Model
{
    use HasFactory;

    protected $fillable = [
        'articulo',
        'cantidad',
        'estado',
        'garantia',
        'observaciones',
        'nroContrato',
        'cliente_id',
        'prestador',
        'recuperador',
        'gramaje',
        'cuello',
    ];

    /**
     * Relación: Un préstamo pertenece a un cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación: Un préstamo tiene un prestador.
     */
    public function prestador()
    {
        return $this->belongsTo(Personal::class, 'prestador');
    }

    /**
     * Relación: Un préstamo tiene un recuperador.
     */
    public function recuperador()
    {
        return $this->belongsTo(Personal::class, 'recuperador');
    }
}
