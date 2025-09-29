<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labor extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',        // Nombre de la labor, ej: "Embotellado"
        'descripcion',   // Detalles de la labor
        'estado',        // Activa/Inactiva
    ];

    /**
     * Relación: Una labor puede tener muchos trabajos
     */
    public function trabajos()
    {
        return $this->hasMany(Trabajo::class);
    }
}
