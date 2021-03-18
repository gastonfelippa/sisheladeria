<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detcompra extends Model
{
    protected $table = 'det_compras';
    protected $fillable = ['id', 'compra_id', 'producto_id', 'cantidad', 'precio', 'comercio_id'];

}
