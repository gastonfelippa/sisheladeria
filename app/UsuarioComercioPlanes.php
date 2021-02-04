<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioComercioPlanes extends Model
{
    protected $table = 'usuariocomercio_planes';
    protected $fillable = ['usuariocomercio_id', 'plan_id', 'estado_plan', 'importe',
                            'estado_pago', 'fecha_inicio_periodo', 'fecha_fin', 'fecha_vto', 'comentarios'];
}
