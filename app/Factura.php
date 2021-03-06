<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';
    protected $fillable = ['numero', 'cliente_id', 'repartidor_id', 'user_id', 
                            'importe', 'estado', 'comercio_id'];
}
