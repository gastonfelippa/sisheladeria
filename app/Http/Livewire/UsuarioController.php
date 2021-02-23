<?php

namespace App\Http\Livewire;

use App\Providers\RouteServiceProvider;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Comercio;
use App\Empleado;
use App\ModelHasRole;
use App\Role;
use App\TipoComercio;
use App\User;
use App\UsuarioComercio;

use App\Events\UserRegistered;
use Illuminate\Mail\Mailable;

use App\Mail\WelcomeUser;
use Illuminate\Support\Facades\Mail;


class UsuarioController extends Component
{
    use RegistersUsers;

    use WithPagination;

    public $tipo = "Elegir", $name, $apellido, $sexo = 0, $telefono1, $email, $direccion = "---";
    public $selected_id, $search, $login = 0;
    public $action = 1, $pagination = 5;
    public $comercioId;
    public $empleado, $rol;
    public $empleados, $roles;
    public $username, $password, $email_empleado;
    public $comercio, $admin;

    public function render()
    {
         //busca el comercio que está en sesión
        $this->comercioId = session('idComercio');
 
        $this->empleados = Empleado::select('*')->where('comercio_id', $this->comercioId)->get();
        $this->roles = Role::select('*')->where('id', '<>', '1')->where('comercio_id', $this->comercioId)->get();
      
        //busca el nombre del comercio logueado y de su admin para el envío de emails
        $comercio = Comercio::select('nombre')
        ->where('id', $this->comercioId)->first(); 
        $this->comercio = $comercio->nombre; 

        $admin = User::join('usuario_comercio as uc', 'uc.usuario_id','users.id')
            ->where('uc.comercio_id', $this->comercioId)
            ->select('users.name', 'users.sexo')
            ->orderBy('users.id', 'asc')->first();
        $this->admin = $admin->name; 
     
        if(strlen($this->search) > 0)
        {
            $info = User::join('usuario_comercio as uc', 'uc.usuario_id', 'users.id')
                ->join('model_has_roles as mhr', 'mhr.model_id', 'users.id')
                ->join('roles', 'roles.id', 'mhr.role_id')
                ->where('users.name', 'like', '%'. $this->search . '%')
                ->where('uc.comercio_id', $this->comercioId)
                ->orwhere('users.apellido', 'like', '%'. $this->search . '%')
                ->where('uc.comercio_id', $this->comercioId)
                ->orwhere('roles.alias', 'like', '%'. $this->search . '%')
                ->where('uc.comercio_id', $this->comercioId)
                ->select('users.*', 'roles.alias')
                ->paginate($this->pagination);
        }
        else
        {
            $info = User::join('usuario_comercio as uc', 'uc.usuario_id', 'users.id')
                ->join('model_has_roles as mhr', 'mhr.model_id', 'users.id')
                ->join('roles', 'roles.id', 'mhr.role_id')
                ->where('uc.comercio_id', $this->comercioId)
                ->select('users.*', 'roles.alias')
                ->paginate($this->pagination);
        }         
        return view('livewire.usuarios.component', ['info' => $info]);
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
        $this->sexo = 0;
        $this->telefono1 = '';
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
        $this->sexo = $record->sexo;
        $this->telefono1 = $record->telefono1;
        $this->email = $record->email;
        $this->direccion = $record->direccion;
        $this->selected_id = $record->id;
        $this->action = 2;
    }

    public function StoreOrUpdate()
    {
        //genera un password random de 8 caracteres y crea una sesion con ese password
        $password = Str::random(8);
        session(['pass_empleado' => $password]);
        session(['empleado' => 'si']);

        $nombre = strtolower($this->name);
        $cadena = Comercio::select('nombre')->where('id', $this->comercioId)->first();
        $cadena = strtolower($cadena->nombre);
        $username = str_replace(' ', '',Str::finish($nombre,'@'. $cadena));
        $this->username = $username;   
        
        $this->validate([
			'sexo' => 'not_in:0'
		]);
        
        $this->validate([
            'name' => 'required',
            'apellido' => 'required', 
            'email' => 'required|email'
            ]);
            
        if($this->selected_id <= 0)
        {
            $user = User::create([
                'name' => ucwords($this->name),
                'apellido' => ucwords($this->apellido),
                'sexo' => $this->sexo,
                'telefono1' => $this->telefono1,
                'direccion' => $this->direccion,
                'email' => strtolower($this->email),
                'username' => $username,
                'password' => Hash::make($password),
                'pass' => $password,
                'abonado' => 'No'
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
                'sexo' => $this->sexo,
                'telefono1' => $this->telefono1,
                'email' => $this->email,
                'direccion' => $this->direccion,
                'username' => $username
                ]);
            }  
            // 'password' => bcrypt($this->password)
                    
        if($this->selected_id)
            session()->flash('message', 'Usuario Actualizado');            
        else
            session()->flash('message', 'Usuario creado exitosamente! Verificar envío de email');       
        
        $this->sendEmail($user, $this->comercio, $this->admin); //falta mensaje de aviso de mensaje enviado

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

    public function sendEmail($user, $comercio, $admin)
    {
        $objDemo = new \stdClass();
        $objDemo->demo_one = $user->username;
        $objDemo->demo_two = session('pass_empleado');
        $objDemo->sender = 'El equipo de FlokI';
        $objDemo->receiver = $user->name;
 
        Mail::to($this->email)->send(new WelcomeUser($user, $comercio, $admin));
    }

}
