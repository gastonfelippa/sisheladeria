<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\CondIva;
use App\Localidad;
use App\Proveedor;
use App\Provincia;

class ProveedorController extends Component
{
   	//public properties
    public $nombre_empresa, $nombre_contacto, $apellido_contacto, $calle, $numero, $localidad = 'Elegir', $provincia = 'Elegir';
    public $tel_empresa, $tel_contacto, $comercioId, $condIvaId = 'Elegir', $cuit; 
    public $selected_id = null, $search, $action = 1; 
   
        //método que se ejecuta después de mount al inciar el componente
    public function render()
    {
        //busca el comercio que está en sesión
        $this->comercioId = session('idComercio');
   
        $localidades = Localidad::select()->orderBy('descripcion','asc')->get();
        $provincias = Provincia::all();
        $condIva = CondIva::all();
           
        //si la propiedad buscar tiene al menos un caracter, devolvemos el componente y le inyectamos los registros de una búsqueda con like y paginado a  5 
        if(strlen($this->search) > 0)
        {
            $info = Proveedor::join('localidades as loc', 'loc.id', 'proveedores.localidad_id')
                ->join('cond_iva as cIva', 'cIva.id', 'proveedores.condiva_id')
                ->where('nombre_empresa', 'like', '%' .  $this->search . '%')
                ->where('comercio_id', $this->comercioId)
                ->orWhere('nombre_contacto', 'like', '%' .  $this->search . '%')
                ->where('comercio_id', $this->comercioId)
                ->orWhere('cuit', 'like', $this->search . '%')
                ->where('comercio_id', $this->comercioId)
                ->orWhere('apellido_contacto', 'like', '%' .  $this->search . '%')
                ->where('comercio_id', $this->comercioId)
                ->select('proveedores.*', 'loc.descripcion as localidad', 'cIva.descripcion as condiva')
                ->orderBy('nombre_empresa', 'asc')->get();
            return view('livewire.proveedores.component', [
                'info' =>$info,
                'localidades' => $localidades,
                'provincias' => $provincias,
                'condIva' => $condIva
            ]);
        }
        else {
            return view('livewire.proveedores.component', [
                'info' => Proveedor::join('localidades as loc', 'loc.id', 'proveedores.localidad_id')
                    ->join('cond_iva as cIva', 'cIva.id', 'proveedores.condiva_id')
                    ->where('comercio_id', $this->comercioId)
                    ->select('proveedores.*', 'loc.descripcion as localidad', 'cIva.descripcion as condiva')
                    ->orderBy('nombre_empresa', 'asc')->get(),
                'localidades' => $localidades,
                'provincias' => $provincias,
                'condIva' => $condIva
            ]);
        }
    }
        //activa la vista edición o creación
    public function doAction($action)
    {
        $this->action = $action;
        $this->resetInput();
    }
   
        //método para reiniciar variables
    private function resetInput()
    {
        $this->nombre_empresa = '';
        $this->tel_empresa = '';
        $this->condIvaId = 'Elegir';
        $this->cuit = '';
        $this->calle = '';
        $this->numero = '';
        $this->localidad = 'Elegir';
        $this->nombre_contacto = '';
        $this->apellido_contacto = '';
        $this->tel_contacto = '';
        $this->selected_id = null;   
        $this->search = '';
    }
   
        //buscamos el registro seleccionado y asignamos la info a las propiedades
    public function edit($id)
    {
        $record = Proveedor::findOrFail($id);
        $this->selected_id = $id;
        $this->nombre_empresa = $record->nombre_empresa;
        $this->tel_empresa = $record->tel_empresa;
        $this->condIvaId = $record->condiva_id;
        $this->cuit = $record->cuit;
        $this->calle = $record->calle;
        $this->numero = $record->numero;
        $this->localidad = $record->localidad_id;
        $this->nombre_contacto = $record->nombre_contacto;
        $this->apellido_contacto = $record->apellido_contacto;
        $this->tel_contacto = $record->tel_contacto;
        $this->action = 2;
    }

    public function validar_cuil($nCuit){
        $nCuit = str_replace('-','',trim($nCuit));
        if (!is_numeric($nCuit)) return false;
        if (strlen($nCuit) != 11) return false;
        
        $factores = array(5,4,3,2,7,6,5,4,3,2);
        $sumatoria = 0;
        for ($i=0; $i < strlen($nCuit)-1; $i++) {
        $sumatoria += (substr($nCuit, $i, 1))*$factores[$i];
        }
        $resto = $sumatoria % 11;
        $digitoVerificador = ($resto != 0) ? (11 - $resto) : $resto;        
        return ($digitoVerificador == substr($nCuit, strlen($nCuit)-1));
    }
   
   
        //método para registrar y/o actualizar info
    public function StoreOrUpdate()
    {         
        $this->validate([
			'localidad' => 'not_in:Elegir',
			'condIvaId' => 'not_in:Elegir'
		]); 

        $this->validate([
            'nombre_empresa' => 'required'
        ]);

        if($this->calle != '' && $this->numero == '') $this->numero = 's/n';  
        
        if($this->cuit != '' && !$this->validar_cuil($this->cuit)) {
            session()->flash('msg-ops', 'Error: CUIT inválido!!');
            return;
        }else {
            if($this->cuit != '') {
                $nCuit = $this->cuit;
                $nCuit = str_replace('-','',trim($nCuit));
                $tipo = substr($nCuit,0,2);
                $numero = substr($nCuit,2,8);
                $codVerificador = substr($nCuit,10,1);
                $this->cuit = $tipo . '-' . $numero . '-' . $codVerificador;
            }
            //valida si existe otro proveedor con el mismo nombre (edicion de proveedores)
            if($this->selected_id > 0) {
                $existe = Proveedor::where('nombre_empresa', $this->nombre_empresa)
                    ->where('comercio_id', $this->comercioId)
                    ->where('id', '<>', $this->selected_id)
                    ->orWhere('cuit', $this->cuit)
                    ->where('comercio_id', $this->comercioId)
                    ->where('id', '<>', $this->selected_id)
                    ->select('*')
                    ->get();
    
                if( $existe->count() > 0) {
                    session()->flash('msg-error', 'Ya existe el Proveedor!!!');
                    $this->resetInput();
                    return;
                }
            }else {
                //valida si existe otro proveedor con el mismo nombre (nuevos registros)
                $existe = Proveedor::where('nombre_empresa', $this->nombre_empresa)
                    ->where('comercio_id', $this->comercioId)
                    ->orWhere('cuit', $this->cuit)
                    ->where('cuit', '<>', '')
                    ->where('comercio_id', $this->comercioId)
                    ->select('*')
                    ->get();
    
                if($existe->count() > 0 ) {
                    session()->flash('msg-error', 'Ya existe el Proveedor');
                    $this->resetInput();
                    return;
                }
            }
   
            if($this->selected_id <= 0) {
                //creamos el registro
                Proveedor::create([
                    'nombre_empresa' => strtoupper($this->nombre_empresa),            
                    'tel_empresa' => $this->tel_empresa,      
                    'condiva_id' => $this->condIvaId,      
                    'cuit' => $this->cuit,            
                    'calle' => ucwords($this->calle),            
                    'numero' => $this->numero,            
                    'localidad_id' => $this->localidad,            
                    'nombre_contacto' => strtoupper($this->nombre_contacto),            
                    'apellido_contacto' => strtoupper($this->apellido_contacto),            
                    'tel_contacto' => $this->tel_contacto,      
                    'comercio_id' => $this->comercioId            
                ]);
            }else {   
                //buscamos el registro
                $record = Proveedor::find($this->selected_id);
                //actualizamos el registro
                $record->update([
                    'nombre_empresa' => strtoupper($this->nombre_empresa),            
                    'tel_empresa' => $this->tel_empresa,      
                    'condiva_id' => $this->condIvaId,      
                    'cuit' => $this->cuit,            
                    'calle' => ucwords($this->calle),            
                    'numero' => $this->numero,            
                    'localidad_id' => $this->localidad,            
                    'nombre_contacto' => strtoupper($this->nombre_contacto),            
                    'apellido_contacto' => strtoupper($this->apellido_contacto),            
                    'tel_contacto' => $this->tel_contacto  
                ]);              
            }   
            //enviamos feedback al usuario
            if($this->selected_id) session()->flash('message', 'Proveedor Actualizado');            
            else session()->flash('message', 'Proveedor Creado');            
        }
        //limpiamos las propiedades
        $this->resetInput();
        return;
    }
    //escuchar eventos y ejecutar acción solicitada
    protected $listeners = [
        'deleteRow'=>'destroy',
        'createFromModal' => 'createFromModal',         
        'createIvaFromModal' => 'createIvaFromModal'         
    ];
       
    public function createFromModal($info)
    {
        $data = json_decode($info);
           
        Localidad::create([
            'descripcion' => ucwords($data->localidad),
            'provincia_id' => $data->provincia_id
        ]);
        session()->flash('message', 'Localidad creada exitosamente!!!');  
    }
    public function createIvaFromModal($info)
    {
        $data = json_decode($info);
           
        CondIva::create([
            'descripcion' => ucwords($data->descripcion)
        ]);
        session()->flash('message', 'Condición de Iva creada exitosamente!!!');  
    }
   
   //método para eliminar un registro dado
    public function destroy($id)
    {
        if ($id) { //si es un id válido
            $record = Proveedor::where('id', $id); //buscamos el registro
            $record->delete(); //eliminamos el registro
            $this->resetInput(); //limpiamos las propiedades
        }
    }
}
