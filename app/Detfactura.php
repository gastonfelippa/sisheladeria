<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detfactura extends Model
{
    protected $table = 'detfacturas';
    protected $fillable = ['id', 'factura_id', 'producto_id', 'cantidad', 'precio'];
}
