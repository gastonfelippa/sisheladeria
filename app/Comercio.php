<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comercio extends Model
{
    protected $table = 'comercios';
    protected $fillable = ['nombre', 'tipo_id'];

}
