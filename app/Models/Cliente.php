<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'empresa',
        'nitCi',
        'razonSocial',
        'telefono',
        'celular',
        'correo',
        'latitud',
        'longitud',
        'foto',
        'estado',
        'verificado',
        'user_id',
        'categoria',
    ];

    /**
     * Relación 1:N con Venta.
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promos()
    {
        // Obtiene todas las promociones asignadas al cliente
        return $this->hasMany(Promo::class);
    }

    // También, si quieres obtener las promociones generales (sin cliente) y las del cliente:
    public function promosVigentes()
    {
        return Promo::where(function ($query) {
            $query->whereNull('cliente_id')  // promociones generales
                ->orWhere('cliente_id', $this->id); // promociones del cliente
        })->where('activo', 1)
            ->where(function ($query) {
                $query->whereNull('fecha_inicio')->orWhere('fecha_inicio', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', now());
            });
    }
}
