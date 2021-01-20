<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Empresa;
use App\UsuarioComercio;
use DB;

class LogoController extends Component
{
    public $nombre, $logo, $nombreComercio;
    
    public function render()
    {  
        $this->event = false;
        $empresa = Empresa::all();
        if($empresa->count() > 0)
        {  
            $this->nombre = $empresa[0]->nombre;
            $this->logo = $empresa[0]->logo;
        }

        $nombreComercio = UsuarioComercio::leftjoin('users as u','u.id','usuario_comercio.usuario_id')
        ->leftjoin('comercios as c','c.id','usuario_comercio.comercio_id')
        ->select('c.nombre')
        ->where('usuario_comercio.usuario_id', Auth()->user()->id)->get();

        if($nombreComercio->count() > 0)
        {  
            $this->nombreComercio = $nombreComercio[0]->nombre;
        }

        return view('livewire.logo.component');
    }
}
