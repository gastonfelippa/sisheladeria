<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistroProceso extends Model
{
    protected $table = 'registro_procesos';
    protected $fillable = ['proceso_id', 'cambios'];
}
