<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Cliente;
use App\Localidad;

class ClienteController extends Component
{
    public $nombre, $apellido, $documento, $calle, $numero, $localidad = 'Elegir';
    public $telefono, $comercioId; 
    public $selected_id = null, $search, $action = 1; 

    public function render()
    {

        //busca el comercio que está en sesión
        $this->comercioId = session('idComercio');

        $localidades = Localidad::all();

        //si la propiedad buscar tiene al menos un caracter, devolvemos el componente y le inyectamos los registros de una búsqueda con like y paginado a  5 
        if(strlen($this->search) > 0)
        {
            $info = Cliente::where('nombre', 'like', '%' .  $this->search . '%')
            ->where('comercio_id', $this->comercioId)
            ->orWhere('apellido', 'like', '%' .  $this->search . '%')
            ->where('comercio_id', $this->comercioId)
            ->orWhere('calle', 'like', '%' .  $this->search . '%')
            ->where('comercio_id', $this->comercioId)
            ->orderBy('apellido', 'asc')->get();

            return view('livewire.clientes.component', [
                'info' =>$info,
                'localidades' => $localidades
            ]);
        }
        else {
           return view('livewire.clientes.component', [
            'info' => Cliente::orderBy('apellido', 'asc')
                        ->where('comercio_id', $this->comercioId)->get(),
            'localidades' => $localidades
        ]);
        }
    }
        //activa la vista edición o creación
    public function doAction($action)
    {
        $this->resetInput();
        $this->action = $action;
    }

        //método para reiniciar variables
    private function resetInput()
    {
        $this->nombre = '';
        $this->apellido = '';
        $this->documento = '';
        $this->calle = '';
        $this->numero = '';
        $this->localidad = 'Elegir';
        $this->telefono = '';
        $this->selected_id = null;       
        $this->action = 1;
        $this->search = '';
    }

        //buscamos el registro seleccionado y asignamos la info a las propiedades
    public function edit($id)
    {
        $record = Cliente::findOrFail($id);
        $this->selected_id = $id;
        $this->nombre = $record->nombre;
        $this->apellido = $record->apellido;
        $this->documento = $record->documento;
        $this->calle = $record->calle;
        $this->numero = $record->numero;
        $this->localidad = $record->localidad_id;
        $this->telefono = $record->telefono;
        $this->action = 2;
    }


//método para registrar y/o actualizar info
    public function StoreOrUpdate()
    {     
            //validación campos requeridos
        $this->validate([
            'nombre' => 'required', //validamos que descripción no sea vacío o nullo y que tenga al menos 4 caracteres
            'apellido' => 'required',
            'calle' => 'required',
            'telefono' => 'required'
        ]);
        if($this->numero == '') $this->numero = 's/n';

        //valida si existe otro cliente con el mismo nombre
        if($this->selected_id > 0) {
            $existe = Cliente::where('nombre', $this->nombre)
            ->where('apellido', $this->apellido)
            ->where('calle', $this->calle)
            ->where('numero', $this->numero)
            ->where('localidad_id', $this->localidad)
            ->where('comercio_id', $this->comercioId)
            ->where('id', '<>', $this->selected_id)
            ->select('*')
            ->get();

            if( $existe->count() > 0) {
                session()->flash('msg-error', 'Ya existe el Cliente');
                $this->resetInput();
                return;
            }
        }        
        else 
        {
            //valida si existe otro cliente con el mismo nombre (nuevos registros)
            $existe = Cliente::where('nombre', $this->nombre)
            ->where('apellido', $this->apellido)
            ->where('calle', $this->calle)
            ->where('numero', $this->numero)
            ->where('localidad_id', $this->localidad)
            ->where('comercio_id', $this->comercioId)
            ->select('*')
            ->get();

            if($existe->count() > 0 ) {
                session()->flash('msg-error', 'Ya existe el Cliente');
                $this->resetInput();
                return;
            }
        }

        if($this->selected_id <= 0) {
            //creamos el registro
            Cliente::create([
                'nombre' => strtoupper($this->nombre),            
                'apellido' => strtoupper($this->apellido),     
                'calle' => ucwords($this->calle),            
                'numero' => $this->numero,            
                'localidad_id' => $this->localidad,            
                'telefono' => $this->telefono,
                'comercio_id' => $this->comercioId            
            ]);
        }
        else 
        {   
            //buscamos la familia
            $record = Cliente::find($this->selected_id);
            //actualizamos el registro
            $record->update([
                'nombre' => strtoupper($this->nombre),            
                'apellido' => strtoupper($this->apellido),     
                'calle' => ucwords($this->calle),            
                'numero' => $this->numero,            
                'localidad_id' => $this->localidad,            
                'telefono' => $this->telefono
            ]);              
        }

        //enviamos feedback al usuario
        if($this->selected_id) {
            session()->flash('message', 'Cliente Actualizado');            
        }
        else {
            session()->flash('message', 'Cliente Creado');            
        }
        //limpiamos las propiedades
        $this->resetInput();
    }


        //escuchar eventos y ejecutar acción solicitada
    protected $listeners = [
        'deleteRow'=>'destroy'        
    ];  

//método para eliminar un registro dado
    public function destroy($id)
    {
        if ($id) { //si es un id válido
            $record = Cliente::where('id', $id); //buscamos el registro
            $record->delete(); //eliminamos el registro
            $this->resetInput(); //limpiamos las propiedades
        }
    }
}
