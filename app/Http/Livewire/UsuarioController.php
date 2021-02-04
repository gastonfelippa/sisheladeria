<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\User;
use App\TipoComercio;
use App\UsuarioComercio;
use App\Empleado;
use App\ModelHasRole;
use App\Role;


class UsuarioController extends Component
{
    use WithPagination;

    public $tipo = "Elegir", $name, $apellido, $telefono, $email, $direccion = "---", $password;
    public $selected_id, $search, $login = 0;
    public $action = 1, $pagination = 5;
    public $comercioId;
    public $empleado, $rol;
    public $empleados, $roles;

    public function render()
    {
         //busca el comercio que está en sesión
        $this->comercioId = session('idComercio');

        $this->empleados = Empleado::select('*')->where('comercio_id', $this->comercioId)->get();
        $this->roles = Role::select('*')->where('id', '<>', '1')->where('comercio_id', $this->comercioId)->get();

        if(strlen($this->search) > 0)
            {
                $info = User::where('name', 'like', '%'. $this->search . '%')
                ->where('uc.comercio_id', $this->comercioId)
                ->orwhere('telefono', 'like', '%'. $this->search . '%')
                ->where('uc.comercio_id', $this->comercioId)
                ->paginate($this->pagination);
                
                return view('livewire.usuarios.component', ['info' => $info]);
            }
            else
            {
                $info = User::leftjoin('usuario_comercio as uc', 'uc.usuario_id', 'users.id')
                ->where('uc.comercio_id', $this->comercioId)
                ->paginate($this->pagination);

                // $info = User::orderBy('id', 'desc')
                // ->paginate($this->pagination);
                
                return view('livewire.usuarios.component', [
                    'info' => $info
                    ]);
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
        $this->name = '';
        $this->apellido = '';
        $this->telefono = '';
        $this->email = '';
        $this->direccion = '';
        $this->password = '';
        $this->selected_id = null;
        $this->action = 1;
        $this->search = '';
    }
    
    public $listeners = [
        'deleteRow' => 'destroy',
        'createFromModal' => 'createFromModal'        
	];

	// es diferente porque se usan ventanas modales
	public function createFromModal($info)
	{
		$data = json_decode($info);
		$this->empleado = $data->empleado_id;
		$this->rol = $data->rol_id;

		$this->AsignarRoles();
    }
    public function AsignarRoles()
    {
        $user_rol = ModelHasRole::create([
            'role_id' => $this->rol,
            'model_type' => 'App\User',
            'model_id' => $this->empleado
        ]);
    }
    
    public function edit($id)
    {
        $record = User::find($id);
        $this->name = $record->name;
        $this->apellido = $record->apellido;
        $this->telefono = $record->telefono;
        $this->email = $record->email;
        $this->direccion = $record->direccion;
        $this->selected_id = $record->id;
        $this->action = 2;
    }

    public function StoreOrUpdate()
    {
        $this->validate([
            'name' => 'required',
            'apellido' => 'required',
            'password' => 'required',
            'email' => 'required|email'
        ]);

        if($this->selected_id <= 0)
        {
            $user = User::create([
                'name' => $this->name,
                'apellido' => $this->apellido,
                'telefono' => $this->telefono,
                'email' => $this->email,
                'direccion' => $this->direccion,
                'password' => bcrypt($this->password),
            ]);

            UsuarioComercio::create([
                'usuario_id' => $user->id,            
                'comercio_id' => $this->comercioId           
            ]);
        }
        else{
            $user = User::find($this->selected_id);
            $user->update([
                'name' => $this->name,
                'apellido' => $this->apellido,
                'telefono' => $this->telefono,
                'email' => $this->email,
                'direccion' => $this->direccion,
                'password' => bcrypt($this->password)
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
