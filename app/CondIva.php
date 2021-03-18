<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CondIva extends Model
{
    protected $table = 'cond_iva';
    protected $fillable = ['descripcion'];
}
