<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignadoReposicion extends Model
{
    use HasFactory;
    protected $table = 'asignado_reposicions';
    protected $fillable = [
        'asignado_id',
        'reposicion_id',
        'existencia_id',
        'cantidad',
        'cantidad_original',
    ];
    public function asignado()
    {
        return $this->belongsTo(Asignado::class);
    }

    public function reposicion()
    {
        return $this->belongsTo(Reposicion::class);
    }
    public function existencia()
    {
        return $this->belongsTo(Existencia::class);
    }
}
