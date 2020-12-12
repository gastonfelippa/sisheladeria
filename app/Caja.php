<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table ='cajas';

    protected $fillable = ['monto','concepto','tipo','user_id'];
}
