<?php

namespace App\Http\Livewire;

use Livewire\Component;
use\App\Auditoria;
use\App\User;
use\App\Gasto;
use DB;

class AuditoriaController extends Component
{          
    public $search, $comercioId, $action = 1;

    public function render()
    {
        //busca el comercio que está en sesión
        $this->comercioId = session('idComercio');

        if(strlen($this->search) > 0) {
            $info = Auditoria::where('tabla', 'like', '%' .  $this->search . '%')
                ->where('comercio_id', $this->comercioId)
                ->orderby('created_at','desc')->get();
        }else {
            $info = Auditoria::join('gastos as g', 'g.id', 'auditorias.item_deleted_id')
            ->join('categorias as c', 'c.id', 'auditorias.item_deleted_id')
            ->join('users as u', 'u.id', 'auditorias.user_delete_id')
            ->where('auditorias.comercio_id', $this->comercioId)
            ->select('auditorias.*', 'g.descripcion as dGasto', 'c.descripcion as dCategoria',
                     'u.name as nomUser', 'u.apellido as apeUser')
            ->orderBy('auditorias.created_at', 'desc')->get();
        }
        return view('livewire.auditorias.component', ['info' =>$info]);
    }

    public function doAction($action)
    {
        $this->search = '';
        $this->action = $action;
    }
}
