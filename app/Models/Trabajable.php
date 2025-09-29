<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajable extends Model
{
    use HasFactory;

    protected $fillable = [
        'trabajo_id',
        'trabajable_id',
        'trabajable_type',
    ];

    /**
     * Relación polimórfica hacia cualquier módulo (Embotellado, Elaboración, Reposición, etc.)
     */
    public function trabajable()
    {
        return $this->morphTo();
    }

    /**
     * Relación hacia el Trabajo
     */
    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class);
    }
}
