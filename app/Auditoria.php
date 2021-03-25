<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table ='auditorias';

    protected $fillable = ['item_deleted_id', 'tabla', 'comentario', 
                           'user_delete_id', 'comercio_id'];
}
