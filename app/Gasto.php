<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gasto extends Model
{
    use SoftDeletes; 
    
    protected $dates = ['deleted_at'];

    protected $table = 'gastos';
    protected $fillable = ['descripcion', 'comercio_id'];
}
