<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use  HasFactory;

    protected $fillable = [
        'login',
        'password',
        'estado',
        'rol_id',
    ];

    /**
     * Relación: Un usuario pertenece a un rol.
     */
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class);
    }

    /**
     * Oculta el campo password en respuestas JSON.
     */
    protected $hidden = [
        'password',
    ];
}
