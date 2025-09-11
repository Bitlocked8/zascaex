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
        'usos_realizados',
        'uso_maximo',
        'estado',
        'fecha_asignada',
        'fecha_expiracion'
    ];
    protected $casts = [
    'fecha_asignada' => 'date',
    'fecha_expiracion' => 'date',
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
