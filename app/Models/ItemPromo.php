<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPromo extends Model
{
    use HasFactory;

    protected $table = 'item_promos';

    protected $fillable = [
        'cliente_id',
        'promo_id',
        'codigo',
        'fecha_asignacion',
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
    ];

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
