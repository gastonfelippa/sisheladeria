<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    protected $table = 'recibos';
    protected $fillable = ['cliente_id', 'importe', 'comentario', 'comercio_id'];
}
