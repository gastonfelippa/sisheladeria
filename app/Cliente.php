<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $fillable = ['nombre', 'apellido', 'calle', 'numero', 'localidad_id',
                           'telefono', 'comercio_id'];
}
