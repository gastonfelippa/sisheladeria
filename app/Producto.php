<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $fillable = ['codigo','descripcion', 'precio_costo', 'precio_venta', 'stock',
                           'estado', 'tipo', 'rubro_id', 'comercio_id'];
}
