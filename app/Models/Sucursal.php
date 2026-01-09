<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'zona',
        'empresa_id',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
    public function existencias()
    {
        return $this->hasMany(Existencia::class);
    }
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function pagos()
    {
        return $this->hasMany(SucursalPago::class);
    }
}
