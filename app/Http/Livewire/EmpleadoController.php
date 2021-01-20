<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Empleado;
use Carbon\Carbon;

class EmpleadoController extends Component
{
	//public properties
	public  $nombre, $direccion, $telefono, $ocupacion, $fecha_ingreso, $fecha_nac; //campos de la tabla empleados
    public  $selected_id = null, $search;   //para búsquedas y fila seleccionada
    public $comercioId;

    //método que se ejecuta después de mount al inciar el componente
    public function render()
    {
        //busca el comercio que está en sesión
        $this->comercioId = session('idComercio');
        
        //si la propiedad buscar tiene al menos un caracter, devolvemos el componente y le inyectamos los registros de una búsqueda con like y paginado a  5 
        if(strlen($this->search) > 0)
        {
            $info = Empleado::where('nombre', 'like', '%' .  $this->search . '%')
                ->where('comercio_id', $this->comercioId)
                ->orderBy('nombre', 'asc')->get();
            return view('livewire.empleados.component', [
                'info' =>$info
            ]);
        }
        else {
            return view('livewire.empleados.component', [
                'info' => Empleado::where('comercio_id', $this->comercioId)
                                    ->orderBy('nombre', 'asc')->get()
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
        $this->ocupacion = 'ATENCIÓN/PÚBLICO';
        $this->fecha_nac = '';
        $this->fecha_ingreso = '';
        $this->selected_id = null;       
        $this->action = 1;
        $this->search = '';
    }

        //buscamos el registro seleccionado y asignamos la info a las propiedades
    public function edit($id)
    {
        $record = Empleado::findOrFail($id);
        $this->selected_id = $id;
        $this->nombre = $record->nombre;
        $this->direccion = $record->direccion;
        $this->telefono = $record->telefono;
        $this->ocupacion = $record->ocupacion;
        $this->fecha_ingreso = Carbon::parse($record->fecha_ingreso)->format('d-m-Y');
        $this->fecha_nac = Carbon::parse($record->fecha_nac)->format('d-m-Y');
        $this->action = 2;
    }


//método para registrar y/o actualizar info
    public function StoreOrUpdate()
    {
     //   dd($this->fecha_nac);     
            //validación campos requeridos
        $this->validate([
            'nombre' => 'required', //validamos que descripción no sea vacío o nullo y que tenga al menos 4 caracteres
            'direccion' => 'required',
            'telefono' => 'required'
        ]);

        //valida si existe otro empleado con el mismo nombre (edicion de empleados)
        if($this->selected_id > 0) {
            $existe = Empleado::where('nombre', $this->nombre)
            ->where('comercio_id', $this->comercioId)
            ->where('id', '<>', $this->selected_id)
            ->select('nombre')
            ->get();

            if( $existe->count() > 0) {
                session()->flash('msg-error', 'Ya existe el Empleado');
                $this->resetInput();
                return;
            }
        }        
        else 
        {
            //valida si existe otro empleado con el mismo nombre (nuevos registros)
            $existe = Empleado::where('nombre', $this->nombre)
            ->where('comercio_id', $this->comercioId)
            ->select('nombre')
            ->get();

            if($existe->count() > 0 ) {
                session()->flash('msg-error', 'Ya existe el Empleado');
                $this->resetInput();
                return;
            }
        }

        if($this->selected_id <= 0) {
            //creamos el registro
            $category =  Empleado::create([
                'nombre' => strtoupper($this->nombre),            
                'direccion' => ucwords($this->direccion),            
                'telefono' => $this->telefono,            
                'ocupacion' => $this->ocupacion,            
                'fecha_ingreso' => Carbon::parse($this->fecha_ingreso)->format('Y,m,d h:i:s'),             
                'fecha_nac' => Carbon::parse($this->fecha_nac)->format('Y,m,d h:i:s'),
                'comercio_id' => $this->comercioId            
            ]);
        }
        else 
        {   
            //buscamos el registro
            $record = Empleado::find($this->selected_id);
            //actualizamos el registro
            $record->update([
                'nombre' => strtoupper($this->nombre),            
                'direccion' => ucwords($this->direccion),            
                'telefono' => $this->telefono,            
                'ocupacion' => $this->ocupacion,            
                'fecha_ingreso' => Carbon::parse($this->fecha_ingreso)->format('Y,m,d h:i:s'),            
                'fecha_nac' => Carbon::parse($this->fecha_nac)->format('Y,m,d h:i:s')  
            ]);              
        }

        //enviamos feedback al usuario
        if($this->selected_id) {
            session()->flash('message', 'Empleado Actualizado');            
        }
        else {
            session()->flash('message', 'Empleado Creado');            
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
            $record = Empleado::where('id', $id); //buscamos el registro
            $record->delete(); //eliminamos el registro
            $this->resetInput(); //limpiamos las propiedades
        }
    }
}
