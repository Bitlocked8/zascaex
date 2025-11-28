<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adornado extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'pedido_id',
        'personal_id',
        'observaciones',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class); 
    }

    public function reposiciones()
    {
        return $this->belongsToMany(Reposicion::class, 'adornado_reposicions')
            ->withPivot('cantidad_usada', 'merma')
            ->withTimestamps();
    }
}
