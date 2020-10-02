<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cajarepartidor extends Model
{
    protected $table = 'cajarepartidor';
    protected $fillable = ['importe', 'tipo', 'estado', 'gasto_id', 'empleado_id'];
}
