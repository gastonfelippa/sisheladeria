<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';
    protected $fillable = ['cliente_id', 'importe', 'estado', 'repartidor_id'];
}
