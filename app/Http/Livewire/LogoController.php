<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Empresa;
use DB;

class LogoController extends Component
{
    public $nombre, $logo;
    
    public function render()
    {  
        $this->event = false;
        $empresa = Empresa::all();
        if($empresa->count() > 0)
        {  
            $this->nombre = $empresa[0]->nombre;
            $this->logo = $empresa[0]->logo;
        }
        return view('livewire.logo.component');
    }
}
