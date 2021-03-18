<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Cliente;
use App\Ctacte;
use App\Recibo;
use DB;

class CtacteController extends Component
{
    //public properties
	public $f_de_pago, $cliente = 'Elegir', $importeCobrado, $comentario, $suma = 0, $sumaFacturas = 0, $sumaRecibos = 0;            //campos de la tabla tipos
    public $selected_id = null, $search, $ver_historial = 0, $verHistorial = 0;   //para búsquedas y fila seleccionada
    public $comercioId;

    public function render()
    {
         //busca el comercio que está en sesión
        $this->comercioId = session('idComercio');

        $clientes = Cliente::select()->where('comercio_id', $this->comercioId)->orderBy('apellido', 'asc')->get();
      
        if(strlen($this->search) > 0){
            if($this->verHistorial == 1){
                $info = Ctacte::join('clientes as c', 'c.id', 'cta_cte.cliente_id')
                    ->where('c.nombre', 'like', '%' .  $this->search . '%')
                    ->where('c.comercio_id', $this->comercioId)
                    ->orWhere('c.apellido', 'like', '%' .  $this->search . '%')
                    ->where('c.comercio_id', $this->comercioId)
                    ->select('cta_cte.factura_id', 'cta_cte.recibo_id', 'cta_cte.created_at as fecha', 
                             'c.nombre', 'c.apellido', DB::RAW("'' as importe"), DB::RAW("'' as imp_factura"))
                    ->orderBy('cta_cte.created_at', 'desc')->get();
            }else {
                $info = Ctacte::join('clientes as c', 'c.id', 'cta_cte.cliente_id')
                    ->where('c.nombre', 'like', '%' .  $this->search . '%')
                    ->where('c.comercio_id', $this->comercioId)
                    ->orWhere('c.apellido', 'like', '%' .  $this->search . '%')
                    ->where('c.comercio_id', $this->comercioId)
                    ->select('cta_cte.cliente_id', 'c.nombre', 'c.apellido', DB::RAW("'' as importe"), DB::RAW("'' as imp_factura"))
                    ->groupBy('cta_cte.cliente_id', 'c.nombre', 'c.apellido')->get();     
            } 
        }else {
            $this->verHistorial = 0;
            $info = Ctacte::join('clientes as c', 'c.id', 'cta_cte.cliente_id')
                ->where('c.comercio_id', $this->comercioId)
                ->select('cta_cte.cliente_id', 'c.nombre', 'c.apellido', DB::RAW("'' as importe"), DB::RAW("'' as imp_factura"))
                ->groupBy('cta_cte.cliente_id', 'c.nombre', 'c.apellido')->get();
        }
        $this->suma=0;
        if($this->verHistorial == 0){
            foreach($info as $i) {
                $this->sumaFacturas=0;
                $this->sumaRecibos=0;
                    //sumo las facturas de cada cliente
                $importe = Ctacte::join('facturas as f', 'f.id', 'cta_cte.factura_id') 
                    ->where('f.cliente_id', $i->cliente_id)
                    ->select('f.importe')->get();
                foreach($importe as $imp){
                    $this->sumaFacturas += $imp->importe; //calculo el total de facturas de cada cliente
                }
                    //sumo los recibos de cada cliente
                $importe = Ctacte::join('recibos as r', 'r.id', 'cta_cte.recibo_id') 
                    ->where('r.cliente_id', $i->cliente_id)
                    ->select('r.importe')->get();
                foreach($importe as $imp){
                    $this->sumaRecibos += $imp->importe; //calculo el total de recibos de cada cliente
                }
                //calculo el total para cada cliente
                $i->importe = $this->sumaRecibos - $this->sumaFacturas;

                //solo calculo el importe del total gral si se están mostrando todos los clientes
                if(strlen($this->search) == 0) $this->suma += $i->importe;

                //pinto el importe de diferente color
                if($i->importe >= 0) $i->imp_factura = 0;
                else $i->imp_factura = 1;
            }
        }elseif(strlen($this->search) > 0 && $this->verHistorial == 1){
            foreach($info as $i) {
                if($i->factura_id != null) {    //busco todas las facturas
                    $importe = Ctacte::join('facturas as f', 'f.id', 'cta_cte.factura_id') 
                        ->where('f.id', $i->factura_id)
                        ->select('f.importe as importe')->get();
                    $i->imp_factura = 1;        //aviso de factura para pintar rojo                   
                }else {                         //busco todos los recibos
                    $importe = Ctacte::join('recibos as r', 'r.id', 'cta_cte.recibo_id')
                        ->where('r.id', $i->recibo_id)                                       
                        ->select('r.importe as importe')->get();
                    $i->imp_factura = 0;        //aviso de recibo para pintar verde
                }
                $i->importe = $importe[0]->importe;

                if($i->imp_factura == 0){
                    $this->suma += $i->importe;
                }else{
                    $this->suma -= $i->importe;
                }
            }
        }
        return view('livewire.ctacte.component', [
            'info'     =>$info,
            'clientes' => $clientes
        ]);
    }

    private function resetInput()
    {
        $this->f_de_pago = '';
        $this->importeCobrado = '';
        $this->cliente = 'Elegir';
        $this->comentario = '';
        $this->selected_id = null;    
        $this->search = '';
        $this->verHistorial = 0;
    }

    public function verHistorial($tipo)
    {
        $this->verHistorial = $tipo;
    }

    public function edit($id)
    {
        $record = Ctacte::findOrFail($id);
        $this->selected_id = $id;
        $this->cliente = $record->cliente_id;
        $this->f_de_pago = $record->created_at;
        $this->importeCobrado = $record->importe;
        $this->comentario = $record->comentario;
    }


//método para registrar y/o actualizar info
    public function StoreOrUpdate()
    { 
        $this->validate([
            'cliente' => 'not_in:Elegir',
            'importeCobrado' => 'required' 
        ]);       
             //creamos el recibo
            $recibo =  Recibo::create([
                'cliente_id' => $this->cliente,            
                'importe' => $this->importeCobrado,
                'comentario' => $this->comentario,
                'comercio_id' => $this->comercioId            
            ]);
            //creamos el registro
            Ctacte::create([
                'cliente_id' => $this->cliente,  
                'recibo_id' => $recibo->id            
            ]);
       
            //enviamos feedback al usuario
        if($this->selected_id) {
            session()->flash('message', 'Recibo Actualizado');            
        }
        else {
            session()->flash('message', 'Recibo Creado');            
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
        // if ($id) { //si es un id válido
        //     $record = Ctacte::where('id', $id); //buscamos el registro
        //     $record->delete(); //eliminamos el registro
        //     $this->resetInput(); //limpiamos las propiedades
        // }
    }
}
