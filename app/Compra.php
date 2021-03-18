<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';
    protected $fillable = ['letra', 'sucursal', 'num_fact', 'proveedor_id', 'user_id', 
                            'importe', 'estado', 'comercio_id'];
}
