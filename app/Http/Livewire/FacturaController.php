<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Detfactura;
use App\Producto;
use App\Factura;
use App\Cliente;
use App\Empleado;
use Carbon\Carbon;
use DB;

class FacturaController extends Component
{
	//properties
    public $cantidad = 1, $precio, $estado='ABIERTO';
    public $cliente="Elegir", $empleado="Elegir", $producto="Elegir", $barcode;
    public $clientes, $empleados, $productos, $dejar_pendiente;
    public $selected_id = null, $search, $id_factura;
    public $facturas, $dirCliente, $total,$importe, $totalAgrabar;  
    public $grabar_encabezado, $habilitar_grabar_encabezado =null, $habilitar_botones =null,$modificar, $codigo;
	
	public function render()
	{
       // $this->facturaAfip();

        $this->productos = Producto::select()->orderBy('descripcion', 'asc')->get();
        $this->clientes = Cliente::select()->orderBy('nombre', 'asc')->get();
        $this->empleados = Empleado::select()->orderBy('nombre', 'asc')->get();

        if(strlen($this->barcode) > 0){ //strlen valida si está vacío o no
            $this->buscarProducto($this->barcode); 
        }
        else{
            $this->precio = '';
        }

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

        if($encabezado->count() > 0){
            if($encabezado[0]->cliente_id != null){
                $this->dejar_pendiente = true;
            }else{
                $this->dejar_pendiente = false;
            }
        }
        $info = Detfactura::all();

        if($info->count() == 0){
            $info = Detfactura::leftjoin('facturas as f','f.id','detfacturas.factura_id')
                    ->leftjoin('productos as p','p.id','detfacturas.producto_id')
                    ->select('detfacturas.*', 'p.descripcion as producto', DB::RAW("'' as importe"))
                    ->where('detfacturas.factura_id', $this->id_factura)
                    ->orderBy('detfacturas.id', 'asc')->get();  
        }    
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
    
    public function facturaAfip()
    {
        include './../resources/src/Afip.php';
        /**
         * CUIT vinculado al certificado
         **/
        $CUIT = 20175835165; 

        $afip = new Afip(array('CUIT' => $CUIT));

        $data = array(
            'CantReg' 	=> 1,  // Cantidad de comprobantes a registrar
            'PtoVta' 	=> 1,  // Punto de venta
            'CbteTipo' 	=> 6,  // Tipo de comprobante (Factura B)(ver tipos disponibles) 
            'Concepto' 	=> 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
            'DocTipo' 	=> 99, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
            'DocNro' 	=> 0,  // Número de documento del comprador (0 consumidor final)
            'CbteDesde' 	=> 1,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
            'CbteHasta' 	=> 1,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
            'CbteFch' 	=> intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
            'ImpTotal' 	=> 121, // Importe total del comprobante
            'ImpTotConc' 	=> 0,   // Importe neto no gravado
            'ImpNeto' 	=> 100, // Importe neto gravado
            'ImpOpEx' 	=> 0,   // Importe exento de IVA
            'ImpIVA' 	=> 21,  //Importe total de IVA
            'ImpTrib' 	=> 0,   //Importe total de tributos
            'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
            'MonCotiz' 	=> 1,     // Cotización de la moneda usada (1 para pesos argentinos)  
            'Iva' 		=> array( // (Opcional) Alícuotas asociadas al comprobante
                array(
                    'Id' 		=> 5, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles) 
                    'BaseImp' 	=> 100, // Base imponible
                    'Importe' 	=> 21 // Importe 
                )
            ), 
        );
        
        $res = $afip->ElectronicBilling->CreateVoucher($data);
        echo $res['CAE']; //CAE asignado el comprobante
        echo $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)
    }
    
    protected $listeners = [
        'buscarProducto' => 'buscarProducto',
        'buscarDomicilio' => 'buscarDomicilio',
        'deleteRow' => 'destroy'         
    ];
    
    public function buscarProducto($id)
    {
        //dd($id);
        $pvta = Producto::select()->where('codigo', $id)->get();
        //dd($pvta);
        if ($pvta->count() > 0){
            $this->producto = $pvta[0]->id;
        }else{
            $this->producto = "Elegir";
            session()->flash('msg-error', 'El Código no existe...');
        } 
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

    public function resetInput()
    {
        $this->cantidad = 1;
        $this->barcode ='';
        $this->precio = '';
        $this->producto = 'Elegir';
        $this->selected_id = null;
        $this->action = 1;
        $this->search ='';
    }

    public function resetInputTodos()
    {
        $this->cantidad = 1;
        $this->barcode ='';
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
       
        DB::begintransaction();                         //iniciar transacción para grabar
        try{  
            if($this->selected_id > 0) {                //valida si se quiere modificar o crear
                $record = DetFactura::find($this->selected_id);  
                $record->update([                       //actualizamos el registro
                    'producto_id' => $this->producto,
                    'cantidad' => $this->cantidad,
                    'precio' => $this->precio
                ]); 
            }else{
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
                        'repartidor_id' => $this->empleado,
                        'user_id' => auth()->user()->id
                    ]);
                }

                $idFactura = Factura::where('estado', 'like', 'ABIERTA')->select('id')->get();
                $existe = Detfactura::select('id')          //buscamos si el producto ya está cargado
                                    ->where('factura_id', 'like', $idFactura[0]->id)
                                    ->where('producto_id', 'like', $this->producto)->get();
                if ($existe->count() > 0){
                    $edit_cantidad = Detfactura::find($existe[0]->id); 
                    $nueva_cantidad = $edit_cantidad->cantidad + $this->cantidad; 
                    $edit_cantidad->update([                //actualizamos solo la cantidad                                      
                        'cantidad' => $nueva_cantidad
                    ]);
                }else{
                    $add_item = Detfactura::create([         //creamos un nuevo detalle
                        'factura_id' => $idFactura[0]->id,
                        'producto_id' => $this->producto,
                        'cantidad' => $this->cantidad,
                        'precio' => $this->precio
                    ]);	
                }

                $record = Factura::find($idFactura[0]->id);  //actualizamos el encabezado
                $record->update([
                    'importe' => $this->totalAgrabar,
                    'cliente_id' => $this->cliente,
                    'repartidor_id' => $this->empleado,
                    'user_id' => auth()->user()->id
                ]); 
            }
                //confirmar la transaccion
            DB::commit();
            if($this->selected_id > 0){		
                session()->flash('message', 'Registro Actualizado');       
            }else{ 
                session()->flash('message', 'Registro Creado'); 
            }           
        }catch (Exception $e){
                DB::rollback();    //en caso de error, deshacemos para no generar inconsistencia de datos
              //  $status = $e->getMessage();
                session()->flash('msg-error', '¡¡¡ATENCIÓN!!! El registro no se grabó...');
               // session()->flash('msg-error', $status);
        }     
        $this->resetInput(); 
        $this->habilitar_grabar_encabezado = false;
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
            $record = Detfactura::where('id', $id);
            $record->delete();
            $this->resetInput();
            $this->emit('msg-ok','Registro eliminado con éxito');
        }
    }
}