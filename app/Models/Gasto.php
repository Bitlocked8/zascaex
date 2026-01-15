<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_id',
        'descripcion',
        'monto',
        'fecha',
        'archivo_evidencia',
    ];


    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
