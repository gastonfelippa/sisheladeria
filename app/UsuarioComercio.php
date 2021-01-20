<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioComercio extends Model
{
    protected $table = 'usuario_comercio';
    protected $fillable = ['id', 'usuario_id', 'comercio_id'];
}
