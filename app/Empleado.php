<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $fillable = ['nombre', 'direccion', 'telefono', 'ocupacion', 'fecha_ingreso', 'fecha_nac'];
}
