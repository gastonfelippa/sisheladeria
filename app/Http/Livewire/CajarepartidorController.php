<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Factura;
use App\Cliente;
use App\Empleado;
use App\DetFactura;
use App\Producto;
use App\Gasto;
use App\Cajarepartidor;
use Carbon\Carbon;
use DB;

class CajarepartidorController extends Component
{
    //properties   
    public $clientes, $empleados, $productos, $productosEdit, $repartidor = '0';
    public $selected_id = null, $search, $id_factura, $fact_id, $infoDetalle;
    public $producto="Elegir", $action='1', $totalfactura, $nombreCliente, $nomRep;
    public $cantidadEdit, $productoEdit, $precioEdit, $editarFactura, $importeFactura;
    public $importe, $gasto, $gastos, $totalCI, $totalCobrado, $totalGastos, $totalCF, $cajaGasto, $cantFacturas;
	
	public function render()
	{
        $this->clientes  = Cliente::all();
        $this->empleados = Empleado::all();
        $this->productos = Producto::all();
        $this->productosEdit = Producto::all();
        $this->gastos = Gasto::all();

        $infoCaja = Cajarepartidor::select('*', DB::RAW("'' as totalCI"))
            ->where('empleado_id','like', $this->repartidor)
            ->where('tipo','like','Ingreso')
            ->where('estado','like','Pendiente')
            ->orderBy('created_at', 'asc')->get();
        $this->totalCI = 0;
        if($infoCaja->count() > 0){             
            foreach ($infoCaja as $i){
                $this->totalCI += $i->importe;
            }
        }

        $infoGastos = Cajarepartidor::leftjoin('gastos as g','g.id','cajarepartidor.gasto_id')
            ->select('cajarepartidor.*', 'g.descripcion', DB::RAW("'' as totalGastos"))
            ->where('empleado_id','like', $this->repartidor)
            ->where('tipo','like','Gasto')
            ->where('estado','like','Pendiente')
            ->orderBy('created_at', 'asc')->get();
        $this->totalGastos = 0;
        if($infoGastos->count() > 0){             
            foreach ($infoGastos as $i){
                $this->totalGastos += $i->importe;
            }
        }

        $nomRep = Empleado::find($this->repartidor);
            if($nomRep != null) {$this->nomRep = $nomRep->nombre;}
                else {$this->repartidor = '0';}
   
        $info = Factura::leftjoin('clientes as c','c.id','facturas.cliente_id')
            ->leftjoin('empleados as r','r.id','facturas.repartidor_id')
            ->select('facturas.*', 'c.nombre as nomcli', 'r.nombre as nomrep', DB::RAW("'' as total"))
            ->where('facturas.estado','like','PENDIENTE')
            ->where('facturas.repartidor_id', 'like', $this->repartidor)
            ->orderBy('facturas.id', 'asc')->get();         
        $this->totalCobrado = 0;  
        $this->cantFacturas = $info->count();      
        if($info->count() > 0){             
            foreach ($info as $i){
                $this->totalCobrado += $i->importe;
            }            
            $this->fact_id = $info[0]->id;
        }

        $this->totalCF = $this->totalCI + $this->totalCobrado - $this->totalGastos;

		return view('livewire.cajarepartidor.component', [
            'info' => $info,
            'infoCaja' => $infoCaja,
            'infoGastos' => $infoGastos
            ]);
    }
    
    protected $listeners = [
        'cobrarFactura'   => 'cobrarFactura',
        'cobrarTodas'     => 'cobrarTodas',
        'deleteRow'       => 'destroy',
        'deleteRowDel'    => 'destroyDel',
        'grabarCajaModal' => 'grabarCajaModal',
        'doAction2'       => 'doAction2' 
    ];

    public function buscarProducto($id)
    {
        $pvta = Producto::find($id);
        $this->precioEdit = $pvta->precio_venta;
    }

    public function verDetalle($id, $cliente)
    {
        $this->action = 2;
        $this->editarFactura = $id;
        $this->nombreCliente = $cliente;
        $this->infoDetalle = DetFactura::leftjoin('facturas as f','f.id','detfacturas.factura_id')
            ->leftjoin('productos as p','p.id','detfacturas.producto_id')
            ->select('detfacturas.*', 'p.descripcion as producto', DB::RAW("'' as importe"))
            ->where('detfacturas.factura_id', $id)
            ->orderBy('detfacturas.id', 'asc')->get(); 

        $this->importeFactura = 0;  

        foreach ($this->infoDetalle as $i)
        {
            $i->importe=$i->cantidad * $i->precio;
            $this->importeFactura += $i->importe;
        }
        $record = Factura::find($this->editarFactura);
        $record->update([
            'importe' => $this->importeFactura
        ]);       
    }

    public function editDel($id)
    {
        $record = DetFactura::find($id);
        $this->selected_id = $id;
        $this->cantidadEdit = $record->cantidad;
        $this->productoEdit = $record->producto_id;
        $this->precioEdit = $record->precio;
        $this->verDetalle($this->editarFactura, $this->nombreCliente);
    }

    public function doAction($action)
	{
        $this->action = $action;
    }

    public function resetInput()
    {
        $this->cantidad = '';
        $this->producto='Elegir';
        $this->precio ='';
        $this->totalDetalle='';
        $this->cantidadEdit = '';
        $this->productoEdit='Elegir';
        $this->precioEdit ='';
        $this->selected_id = 0;
       
    }

    public function StoreOrUpdate()
    {
        $this->validate([
            'productoEdit' => 'not_in:Elegir'
        ]);            
        $this->validate([
            'cantidadEdit' => 'required',
            'productoEdit' => 'required',
            'precioEdit' => 'required'
        ]);
        //valida si se quiere modificar o grabar
        if($this->selected_id > 0) {
            $record = DetFactura::find($this->selected_id);
            $record->update([
                'cantidad' => $this->cantidadEdit,
                'producto_id' => $this->productoEdit,
                'precio' => $this->precioEdit
            ]);
        }else {            
            DB::begintransaction();         //iniciar transacción para grabar
            try{
                $cajon = DetFactura::create([
                    'factura_id' => $this->editarFactura,
                    'cantidad' => $this->cantidadEdit,
                    'producto_id' => $this->productoEdit,
                    'precio' => $this->precioEdit
                ]);	              

                if($this->selected_id > 0)		
                    session()->flash('message', 'Registro Actualizado');       
                else 
                    session()->flash('message', 'Registro Creado');             
                DB::commit();               //confirmar la transaccion
            }catch (\Exception $e){
                DB::rollback();             //en caso de error, deshacemos para no generar inconsistencia de datos
                $status = $e->getMessage();
                session()->flash('msg-error', '¡¡¡ATENCIÓN!!! El registro no se grabó...');
            }
        }
        $this->verDetalle($this->editarFactura, $this->nombreCliente);
        $this->resetInput();
    }

    public function cobrarFactura($id){
        if($this->cantFacturas == 1){ //si elimina la última factura pendiente, también elimina caja y gastos
            $record = Cajarepartidor::select('estado')->where('empleado_id',$this->repartidor)->where('estado', 'like','Pendiente');
            $record->update([
                'estado' => 'Terminado'
            ]);
        }

        $record = Factura::find($id); 
        $record->update([
            'estado' => 'PAGADA'
        ]);
    }
    public function cobrarTodas($repId){

        $record = Factura::select('estado')->where('repartidor_id',$repId)->where('estado', 'like','PENDIENTE');
        $record->update([
            'estado' => 'PAGADA'
        ]); 
        $record = Cajarepartidor::select('estado')->where('empleado_id',$repId)->where('estado', 'like','Pendiente');
        $record->update([
            'estado' => 'Terminado'
        ]); 
    }

    public function destroy($id) //eliminar/anular desde el modal
    {      
        if($id) {
            $record = Cajarepartidor::where('id', $id);
            $record->delete();
            $this->emit('msg-ok','Registro eliminado con éxito');
        }
    }

    public function destroyDel($id) //eliminar item
    {
        if($id) {
            $record = DetFactura::where('id', $id);
            $record->delete();

            $this->verDetalle($this->editarFactura, $this->nombreCliente);
            $this->resetInput();
            $this->emit('msg-ok','Registro eliminado con éxito');
        }
    }
    	// es diferente porque se usan ventanas modales
	public function grabarCajaModal($info)
	{
        //dd($info, $this->repartidor);
		$data = json_decode($info);
		$this->selected_id = $data->id;
        $this->importe = $data->importe;
        //if($data->gasto != ''){
            $this->gasto = $data->gasto;
        // }
        // else{
        //     $this->gasto = null;
        // }
		$this->cajaGasto = $data->cajaGasto;

		$this->StoreOrUpdateCaja();
    }
    
    public function StoreOrUpdateCaja()
    {
        if($this->cajaGasto == 1) {
           $this->gasto = null; 
        } 

        //valida si se quiere modificar o grabar   
        if($this->selected_id > 0) {
            $record = Cajarepartidor::find($this->selected_id);
            $record->update([
                'importe' => $this->importe,               
                'gasto_id' => $this->gasto                        
            ]);
        }else {          
            DB::begintransaction();         //iniciar transacción para grabar
            try{
                if($this->cajaGasto == 1){    
                    Cajarepartidor::create([
                        'importe'     => $this->importe,
                        'tipo'        => 'Ingreso',
                        'estado'      => 'Pendiente',
                        'empleado_id' => $this->repartidor
                    ]); 
                }else{
                    Cajarepartidor::create([
                        'importe'     => $this->importe,
                        'tipo'        => 'Gasto',
                        'estado'      => 'Pendiente',
                        'empleado_id' => $this->repartidor,
                        'gasto_id'    => $this->gasto
                    ]); 
                }               	              

                if($this->selected_id > 0)		
                    session()->flash('message', 'Registro Actualizado');       
                else 
                    session()->flash('message', 'Registro Creado');             
                DB::commit();               //confirmar la transaccion
            }catch (\Exception $e){
                DB::rollback();             //en caso de error, deshacemos para no generar inconsistencia de datos
                $status = $e->getMessage();
                session()->flash('msg-error', '¡¡¡ATENCIÓN!!! El registro no se grabó...');
            }
        }
        $this->resetInput();
    }
}
