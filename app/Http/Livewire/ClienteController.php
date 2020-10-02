<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Cliente;

class ClienteController extends Component
{
	//public properties
	public  $nombre, $direccion, $telefono;            //campos de la tabla clientes
	public  $selected_id = null, $search;   //para búsquedas y fila seleccionada

    public function render()
    {
        //si la propiedad buscar tiene al menos un caracter, devolvemos el componente y le inyectamos los registros de una búsqueda con like y paginado a  5 
        if(strlen($this->search) > 0)
        {
            $info = Cliente::where('nombre', 'like', '%' .  $this->search . '%')
                ->orWhere('direccion', 'like', '%' .  $this->search . '%')
                ->orderBy('nombre', 'asc')->get();
            return view('livewire.clientes.component', [
                'info' =>$info
            ]);
        }
        else {
           return view('livewire.clientes.component', [
            'info' => Cliente::orderBy('nombre', 'asc')->get()
        ]);
       }
   }
        //activa la vista edición o creación
    public function doAction($action)
    {
        $this->resetInput();
    }

        //método para reiniciar variables
    private function resetInput()
    {
        $this->nombre = '';
        $this->direccion = '';
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
        $this->direccion = $record->direccion;
        $this->telefono = $record->telefono;
        $this->action = 2;
    }


//método para registrar y/o actualizar info
    public function StoreOrUpdate()
    {     
            //validación campos requeridos
        $this->validate([
            'nombre' => 'required', //validamos que descripción no sea vacío o nullo y que tenga al menos 4 caracteres
            'direccion' => 'required'
        ]);

        //valida si existe otro cajón con el mismo nombre (edicion de familias)
        if($this->selected_id > 0) {
            $existe = Cliente::where('nombre', $this->nombre)
            ->where('id', '<>', $this->selected_id)
            ->select('nombre')
            ->get();

            if( $existe->count() > 0) {
                session()->flash('msg-error', 'Ya existe el Cliente');
                $this->resetInput();
                return;
            }
        }        
        else 
        {
            //valida si existe otro cajón con el mismo nombre (nuevos registros)
            $existe = Cliente::where('nombre', $this->nombre)
            ->select('nombre')
            ->get();

            if($existe->count() > 0 ) {
                session()->flash('msg-error', 'Ya existe el Cliente');
                $this->resetInput();
                return;
            }
        }

        if($this->selected_id <= 0) {
            //creamos el registro
            $category =  Cliente::create([
                'nombre' => strtoupper($this->nombre),            
                'direccion' => ucwords($this->direccion),            
                'telefono' => $this->telefono            
            ]);
        }
        else 
        {   
            //buscamos la familia
            $record = Cliente::find($this->selected_id);
            //actualizamos el registro
            $record->update([
                'nombre' => strtoupper($this->nombre),            
                'direccion' => ucwords($this->direccion),            
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
