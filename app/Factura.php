<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use SoftDeletes; 
    
    protected $dates = ['deleted_at'];
    protected $table = 'facturas';
    protected $fillable = ['numero', 'cliente_id', 'repartidor_id', 'user_id', 
                            'importe', 'estado', 'user_id_delete', 'comentario', 'comercio_id'];
}
