<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoComercio extends Model
{
    protected $table = 'tipo_comercio';
    protected $fillable = ['descripcion']; 
}
