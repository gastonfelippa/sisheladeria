<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\User;


class UsuarioController extends Component
{
    use WithPagination;

    public $tipo = "Elegir", $nombre, $apellido, $telefono, $movil, $email, $direccion = "---", $password;
    public $selected_id, $search, $login = 0;
    public $action = 1, $pagination = 5;

    public function render()
    {
        if(strlen($this->search) > 0)
            {
                $info = User::where('nombre', 'like', '%'. $this->search . '%')
                ->orwhere('telefono', 'like', '%'. $this->search . '%')
                ->paginate($this->pagination);
                
                return view('livewire.usuarios.component', ['info' => $info]);
            }
            else
            {
                $info = User::orderBy('id', 'desc')
                ->paginate($this->pagination);
                
                return view('livewire.usuarios.component', ['info' => $info]);
            }        
    }
    
    public function updatingSearch()
    {
        $this->gotoPage(1);
    }
    
    public function doAction($action)
    {
        $this->resetInput();
        $this->action = $action;
    }
    
    public function resetInput()
    {
        $this->nombre = '';
        $this->apellido = '';
        $this->telefono = '';
        $this->movil = '';
        $this->email = '';
        // $this->tipo = 'Elegir';
        $this->direccion = '';
        $this->password = '';
        $this->selected_id = null;
        $this->action = 1;
        $this->search = '';
    }
    
    public $listeners = [
        'deleteRow' => 'destroy'
    ];
    
    public function edit($id)
    {
        $record = User::find($id);
        $this->nombre = $record->nombre;
        $this->apellido = $record->apellido;
        $this->telefono = $record->telefono;
        $this->movil = $record->movil;
        $this->email = $record->email;
        // $this->tipo = $record->tipo;
        $this->direccion = $record->direccion;
        $this->selected_id = $record->id;
        $this->action = 2;
    }

    public function StoreOrUpdate()
    {
        $this->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'password' => 'required',
            'email' => 'required|email'
            // 'tipo' => 'not_in:Elegir'
            // 'tipo' => 'required',
        ]);

        if($this->selected_id <= 0)
        {
            $user = User::create([
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'telefono' => $this->telefono,
                'movil' => $this->movil,
                'email' => $this->email,
                'direccion' => $this->direccion,
                'password' => bcrypt($this->password)
                // 'tipo' => $this->tipo,
            ]);
        }
        else{
            $user = User::find($this->selected_id);
            $user->update([
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'telefono' => $this->telefono,
                'movil' => $this->movil,
                'email' => $this->email,
                'direccion' => $this->direccion,
                'password' => bcrypt($this->password)
                // 'tipo' => $this->tipo,
            ]);
        }  

        if($this->selected_id) {
            session()->flash('message', 'Usuario Actualizado');            
        }
        else {
            session()->flash('message', 'Usuario Creado');            
        }
        //limpiamos las propiedades
        $this->resetInput();  
    }  
    
    public function destroy($id)
    {
        if ($id) { //si es un id válido
            $record = User::where('id', $id); //buscamos el registro
            $record->delete(); //eliminamos el registro
            $this->resetInput(); //limpiamos las propiedades
        }
    }

}
