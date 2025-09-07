<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reposicion extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'cantidad',
        'base_id',
        'personal_id',
    ];

    /**
     * Relación: Una reposición pertenece a una base.
     */
    public function base()
    {
        return $this->belongsTo(Base::class);
    }

    /**
     * Relación: Una reposición pertenece a un personal.
     */
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
