<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReposicionTraspaso extends Model
{
    /** @use HasFactory<\Database\Factories\ReposicionTraspasoFactory> */
    use HasFactory;

    protected $fillable = [
        'traspaso_id',
        'reposicion_id',
        'cantidad',
    ];
}
