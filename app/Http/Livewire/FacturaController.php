<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\DetFactura;
use App\Producto;
use App\Factura;
use App\Cliente;
use App\Empleado;
use Carbon\Carbon;
use DB;

class FacturaController extends Component
{
	//properties
    public $cantidad, $precio, $estado='ABIERTO';
    public $cliente="Elegir", $empleado="Elegir", $producto="Elegir";
    public $clientes, $empleados, $productos;
    public $selected_id = null, $search, $id_factura;
    public $facturas, $dirCliente, $total,$importe, $totalAgrabar;  
    public $grabar_encabezado, $habilitar_grabar_encabezado =null, $habilitar_botones =null,$modificar, $codigo;
	
	public function render()
	{
        $this->productos = Producto::select()->orderBy('descripcion', 'asc')->get();
        $this->clientes = Cliente::select()->orderBy('nombre', 'asc')->get();
        $this->empleados = Empleado::select()->orderBy('nombre', 'asc')->get();

        $dCliente = Cliente::find($this->cliente);
            if($dCliente != null) {$this->dirCliente = $dCliente->direccion;}
        $dProducto = Producto::find($this->producto);
            if($dProducto != null) {$this->precio = $dProducto->precio_venta;}else {$this->precio = '';}

        $encabezado = Factura::all();
        if($encabezado->count() == 0){
            $this->id_factura = 1;
            $this->grabar_encabezado = true;
        }else{
            $encabezado = Factura::leftjoin('clientes as c','c.id','facturas.cliente_id')
            ->leftjoin('empleados as r','r.id','facturas.repartidor_id')
            ->where('facturas.estado','like','ABIERTA')
            ->select('facturas.*', 'c.nombre as nomCli','c.direccion', 'r.nombre as nomRep')->get();

            if($encabezado->count() > 0){
                $this->id_factura = $encabezado[0]->id;
                if($this->habilitar_grabar_encabezado)  
                    $this->grabar_encabezado = true;
                else   
                    $this->grabar_encabezado = false;                             
            }else{
                $encabezado = Factura::select('facturas.id')
                            ->orderBy('facturas.id', 'desc')->get();                             
                $this->id_factura = $encabezado[0]->id + 1;
                $this->grabar_encabezado = true;
            }
        }
           
        $info = DetFactura::leftjoin('facturas as f','f.id','detfacturas.factura_id')
                ->leftjoin('productos as p','p.id','detfacturas.producto_id')
                ->select('detfacturas.*', 'p.descripcion as producto', DB::RAW("'' as importe"))
                ->where('detfacturas.factura_id', $this->id_factura)
                ->orderBy('detfacturas.id', 'asc')->get();  

        $this->total = 0;

        foreach ($info as $i)
        {
            $i->importe=$i->cantidad * $i->precio;
            $this->total += $i->importe;
        }

		return view('livewire.facturas.component', [
            'info' => $info,
            'encabezado' => $encabezado
		]);
	}
    
    protected $listeners = [
        'buscarProducto' => 'buscarProducto',
        'buscarDomicilio' => 'buscarDomicilio',
        'buscarProductoPorCodigo' => 'buscarProductoPorCodigo',
        'deleteRow' => 'destroy'         
    ];
    
    public function buscarProducto($id)
    {
        $pvta = Producto::find($id);
        $this->precio = $pvta->precio_venta;
        $this->totalAgrabar = $this->total + ($this->cantidad * $this->precio);
    }

    public function buscarDomicilio($id)
    {
        if($id > 0){
            $dom = Cliente::find($id);
            $this->dirCliente = $dom->direccion;
        }else{
            $this->dirCliente = '';
        }
    }

    public function buscarProductoPorCodigo($id)
    {
        $pvta = Producto::find($id);        
        $this->producto = $pvta->descripcion;
        $this->precio = $pvta->precio_venta;
    }

    public function resetInput()
    {
        $this->cantidad = '';
        $this->precio = '';
        $this->producto = 'Elegir';
        $this->selected_id = null;
        $this->action =1;
        $this->search ='';
    }

    public function resetInputTodos()
    {
        $this->cantidad = '';
        $this->precio = '';        
        $this->cliente = 'Elegir';
        $this->dirCliente = null;
        $this->empleado = 'Elegir';
        $this->producto = 'Elegir';
        $this->selected_id = null;
        $this->action =1;
        $this->search ='';
        $this->habilitar_grabar_encabezado = true;
        $this->habilitar_botones = false;
    }

    public function edit($id)
    {
        $record = DetFactura::find($id);
        $this->selected_id = $id;
        $this->producto = $record->producto_id;
        $this->precio = $record->precio;
        $this->cantidad = $record->cantidad;
        $this->action = 2;
    }

    public function StoreOrUpdate()
    {
        $this->validate([
            'producto' => 'not_in:Elegir'
        ]);
            
        $this->validate([
            'cantidad' => 'required',
            'producto' => 'required',
            'precio' => 'required'
        ]);
        $this->totalAgrabar = $this->total + ($this->cantidad * $this->precio);
        //valida si se quiere modificar o grabar
        if($this->selected_id > 0) {
            //buscamos el tipo
            $record = DetFactura::find($this->selected_id);
            //actualizamos el registro
            $record->update([
                'producto_id' => $this->producto,
                'cantidad' => $this->cantidad,
                'precio' => $this->precio
            ]); 
        }        
        else 
        {
            //iniciar transacción para grabar
            DB::begintransaction();
            try{
                if($this->cliente == 'Elegir'){
                    $this->cliente = null;
                }
                if($this->empleado == 'Elegir'){
                    $this->empleado = null;
                }   

                if($this->grabar_encabezado == true)
                {
                    $cajon = Factura::create([
                        'cliente_id' => $this->cliente,
                        'importe' => $this->totalAgrabar,
                        'estado' => 'ABIERTA',
                        'repartidor_id' => $this->empleado
                    ]);
                }
                $idFactura = Factura::where('estado', 'like', 'ABIERTA')->select('id')->get();

                $cajon = DetFactura::create([
                    'factura_id' => $idFactura[0]->id,
                    'producto_id' => $this->producto,
                    'cantidad' => $this->cantidad,
                    'precio' => $this->precio
                ]);	
                $record = Factura::find($idFactura[0]->id);
                //actualizamos el registro
                $record->update([
                    'importe' => $this->totalAgrabar,
                    'cliente_id' => $this->cliente,
                    'repartidor_id' => $this->empleado
                ]); 


                if($this->selected_id > 0)		
                    session()->flash('message', 'Registro Actualizado');       
                else 
                    session()->flash('message', 'Registro Creado'); 

                $this->resetInput(); 
                $this->habilitar_grabar_encabezado = false;

                //     //confirmar la transaccion
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();    //en caso de error, deshacemos para no generar inconsistencia de datos
                $status = $e->getMessage();
              //  session()->flash('msg-error', '¡¡¡ATENCIÓN!!! El registro no se grabó...');
                session()->flash('msg-error', $status);
            }
         }
    }
    
    public function terminar_factura()
    {
        if($this->total != 0){
            $record = Factura::find($this->id_factura);
            $record->update([
                'estado' => 'PAGADA',
                'importe' => $this->total
            ]);              
            session()->flash('message', 'Factura Cobrada'); 
            $this->resetInputTodos();
        }else{
            session()->flash('msg-error', 'Factura vacía...'); 
        }
    }
        
    public function dejar_pendiente()
    {
        if($this->total == 0){
            session()->flash('msg-error', 'Factura vacía...'); 
        }elseif($this->cliente == null || $this->empleado == null){
            session()->flash('msg-error', 'Campos vacíos...'); 
        }else{
            $record = Factura::find($this->id_factura);
            $record->update([
                'estado' => 'PENDIENTE',
                'importe' => $this->total
            ]);              
            session()->flash('message', 'Factura Pendiente'); 
            $this->habilitar_grabar_encabezado = true;
            $this->resetInputTodos();
        }
    }
    
    public function cancelarModEncabezado()
    { 
        $this->habilitar_grabar_encabezado = false;
        $this->habilitar_botones = false;
    }
    public function modificarEncabezado()
    { 
        $this->habilitar_grabar_encabezado = true;
        $this->habilitar_botones = true;
    }
    public function grabarModEncabezado($id)
    { 
        $this->validate([
            'cliente' => 'not_in:Elegir',
            'empleado' => 'not_in:Elegir'
        ]);
        $record = Factura::find($id);
        $record->update([
            'cliente_id' => $this->cliente,
            'repartidor_id' => $this->empleado
        ]);
        $this->habilitar_grabar_encabezado = false;
        $this->habilitar_botones = false;
        session()->flash('message', 'Encabezado Modificado...');
    }

    public function destroy($id) //eliminar / delete / remove
    {
        if($id) {
            $record = DetFactura::where('id', $id);
            $record->delete();
            $this->resetInput();
            $this->emit('msg-ok','Registro eliminado con éxito');
        }
    }
}