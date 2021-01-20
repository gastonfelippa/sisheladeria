<?php

namespace App\Http\Livewire;

use Livewire\Component;
use\App\Gasto;

class GastoController extends Component
{
    //public properties
	public  $descripcion;            //campos de la tabla gastos
    public  $selected_id, $search;   //para búsquedas y fila seleccionada
    public $comercioId;

    public function render()
    {
          //busca el comercio que está en sesión
          $this->comercioId = session('idComercio');

        if(strlen($this->search) > 0)
        {
            $info = Gasto::where('descripcion', 'like', '%' .  $this->search . '%')
                    ->where('comercio_id', $this->comercioId)
                    ->orderby('descripcion','desc')->get();
            return view('livewire.gastos.component', [
                'info' =>$info
            ]);
        }
        else {
           return view('livewire.gastos.component', [
            'info' => Gasto::orderBy('descripcion', 'asc')
                            ->where('comercio_id', $this->comercioId)->get()
        ]);
       }
    }

    public function doAction($action)
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->descripcion = '';
        $this->selected_id = null;    
        $this->search = '';
    }

    public function edit($id)
    {
        $record = Gasto::findOrFail($id);
        $this->selected_id = $id;
        $this->descripcion = $record->descripcion;
    }


//método para registrar y/o actualizar info
    public function StoreOrUpdate()
    { 
            //validación campos requeridos
        $this->validate([
            'descripcion' => 'required' //validamos que descripción no sea vacío o nullo y que tenga al menos 4 caracteres
        ]);

        //valida si existe otro cajón con el mismo nombre (edicion de tipos)
        if($this->selected_id > 0) {
            $existe = Gasto::where('descripcion', $this->descripcion)
            ->where('id', '<>', $this->selected_id)
            ->where('comercio_id', $this->comercioId)
            ->select('descripcion')
            ->get();

            if( $existe->count() > 0) {
            session()->flash('msg-error', 'Ya existe el Gasto');
            $this->resetInput();
            return;
            }
        }        
        else 
        {
            //valida si existe otro cajón con el mismo nombre (nuevos registros)
            $existe = Gasto::where('descripcion', $this->descripcion)
            ->where('comercio_id', $this->comercioId)
            ->select('descripcion')
            ->get();

            if($existe->count() > 0 ) {
            session()->flash('msg-error', 'Ya existe el Gasto');
            $this->resetInput();
            return;
            }
        }
        if($this->selected_id <= 0) {
            //dd($this->descripcion,$this->margen);
            //creamos el registro
            $category =  Gasto::create([
                'descripcion' => strtoupper($this->descripcion),
                'comercio_id' => $this->comercioId
            ]);
        }
        else 
        {   
            //buscamos el tipo
            $record = Gasto::find($this->selected_id);
            //actualizamos el registro
            $record->update([
            'descripcion' => strtoupper($this->descripcion)
            ]);              
        }
            //enviamos feedback al usuario
        if($this->selected_id) {
            session()->flash('message', 'Gasto Actualizado');            
        }
        else {
            session()->flash('message', 'Gasto Creado');            
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
            $record = Gasto::where('id', $id); //buscamos el registro
            $record->delete(); //eliminamos el registro
            $this->resetInput(); //limpiamos las propiedades
        }
    }
}
