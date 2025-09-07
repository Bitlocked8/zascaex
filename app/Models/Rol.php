<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación: Un rol tiene muchos usuarios (1:N).
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class); // Relación con el modelo User
    }
}