<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use SoftDeletes; 
    
    protected $dates = ['deleted_at'];
    protected $table = 'proveedores';
    protected $fillable = ['nombre_empresa', 'tel_empresa', 'condiva_id', 'cuit', 'calle', 'numero', 'localidad_id', 
                           'nombre_contacto', 'apellido_contacto', 'tel_contacto', 'comercio_id'];
}
