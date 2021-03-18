<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Cliente;
use App\Ctacte;
use App\Detfactura;
use App\Factura;
use App\Producto;
use App\Vianda;
use Carbon\Carbon;
use DB;

class ViandasController extends Component
{

    public $comercioId, $fecha, $check, $producto, $dia;
    public $numero, $cliente_id, $importe, $factura_id, $producto_id, $cantidad, $precio;

    public function render()
    {
         //busca el comercio que está en sesión
        $this->comercioId = session('idComercio');

        $productos = Producto::select()->where('comercio_id', $this->comercioId)->orderBy('descripcion', 'asc')->get();
        
        $diaDeLaSemana = '';
        if($this->fecha != '') $diaDeLaSemana = $this->fecha;
        else $diaDeLaSemana = date('w');
        switch ($diaDeLaSemana) {
            case '1': $this->dia = 'lunes'; break;
            case '2': $this->dia = 'martes'; break;
            case '3': $this->dia = 'miercoles'; break;
            case '4': $this->dia = 'jueves'; break;
            case '5': $this->dia = 'viernes'; break;
            case '6': $this->dia = 'sabado'; break;
            case '0': $this->dia = 'domingo'; break;
            default:
        }
        
        $info = Vianda::join('clientes as c', 'c.id', 'viandas.cliente_id')
            ->join('productos as p', 'p.id', 'viandas.producto_id')
            ->where('c.vianda', '1')
            ->where('viandas.c_'. $this->dia .'_m', '<>', '')
            ->where('c.comercio_id', $this->comercioId)
            ->select('viandas.c_'. $this->dia .'_m as cantidad','viandas.h_'. $this->dia .'_m as hora', 'viandas.comentarios',
            'c.id as cliente_id', 'c.apellido', 'c.nombre', 'p.descripcion', 'p.precio_venta', 
            DB::RAW("'' as importe"))->orderBy('h_'. $this->dia .'_m')->get(); 
        foreach ($info as $i){
            $i->importe = $i->cantidad * $i->precio_venta;
        }
        return view('livewire.viandas.component', [
            'info' => $info,
            'productos' => $productos
            ]);
        }
        
        public function cambiarFecha($data)
        {
            if($data != '') $this->fecha = date('w',strtotime($data));
    }

    public function grabar($data)
    { 
       // dd($data);
        $data = json_decode($data);     

        DB::begintransaction();                 //iniciar transacción para grabar
        try{
            $info = Vianda::join('clientes as c', 'c.id', 'viandas.cliente_id')
                ->join('productos as p', 'p.id', 'viandas.producto_id')
                ->where('c.vianda', '1')
                ->where('viandas.c_'. $this->dia .'_m', '<>', '')
                ->where('c.comercio_id', $this->comercioId)
                ->select('viandas.c_'. $this->dia .'_m as cantidad', 'c.id as cliente_id', 'p.id as producto_id',
                         'p.precio_venta', DB::RAW("'' as importe"))->get(); 
            foreach ($info as $i){
                $i->importe=$i->cantidad * $i->precio_venta;
            }
           
            foreach($info as $i){
                if (in_array($i->cliente_id, $data)) {

                    $primerFactura = Factura::select('*')->where('comercio_id', $this->comercioId)->get();                       
                    if($primerFactura->count() == 0){
                        $numFactura = 1;
                    }else{
                        $encabezado = Factura::select('facturas.numero')
                                    ->where('comercio_id', $this->comercioId)
                                    ->orderBy('facturas.numero', 'desc')->get();                             
                        $numFactura = $encabezado[0]->numero + 1;
                    }
                
                    $factura = Factura::create([
                        'numero' => $numFactura,
                        'cliente_id' => $i->cliente_id,
                        'importe' => $i->importe,
                        'estado' => 'CTACTE',
                        'user_id' => auth()->user()->id,
                        'comercio_id' => $this->comercioId
                    ]);
                    Detfactura::create([         //creamos un nuevo detalle
                        'factura_id' => $factura->id,
                        'producto_id' => $i->producto_id,
                        'cantidad' => $i->cantidad,
                        'precio' => $i->precio_venta,
                        'comercio_id' => $this->comercioId
                    ]);	
                    Ctacte::create([
                        'cliente_id' => $i->cliente_id,
                        'factura_id' => $factura->id
                    ]);                
                }
            }
            DB::commit();
            session()->flash('message', 'Facturas de Viandas creadas exitosamente!!');
            return;
        }catch (Exception $e){
            DB::rollback();    
            session()->flash('msg-error', '¡¡¡ATENCIÓN!!! El registro no se grabó...');
            return;          
        }
    }
    protected $listeners = [       
        'grabar'=>'grabar',         
        'cambiarFecha'=>'cambiarFecha',         
        'createFactFromModal' => 'createFactFromModal'         
    ];
       
    public function createFactFromModal($infoMod)
    {
        $data = json_decode($infoMod);

        DB::begintransaction();                 //iniciar transacción para grabar
        try{

            $preVta = Producto::select('precio_venta')->where('id', $data->producto_id)->get();
            $importe = 0;
            $importe = $data->cantidad * $preVta[0]->precio_venta;            
           
            $primerFactura = Factura::select('*')->where('comercio_id', $this->comercioId)->get();                       
            if($primerFactura->count() == 0){
                $numFactura = 1;
            }else{
                $ultimaFactura = Factura::select('facturas.numero')
                                    ->where('comercio_id', $this->comercioId)
                                    ->orderBy('facturas.numero', 'desc')->get();                             
                $numFactura = $ultimaFactura[0]->numero + 1;
            }   

            $factura = Factura::create([
                'numero' => $numFactura,
                'cliente_id' => $data->cliente_id,
                'importe' => $importe,
                'estado' => 'CTACTE',
                'user_id' => auth()->user()->id,
                'comercio_id' => $this->comercioId
            ]);
            Detfactura::create([         //creamos un nuevo detalle
                'factura_id' => $factura->id,
                'producto_id' => $data->producto_id,
                'cantidad' => $data->cantidad,
                'precio' => $preVta[0]->precio_venta,
                'comercio_id' => $this->comercioId
            ]);	
            Ctacte::create([
                'cliente_id' => $data->cliente_id,
                'factura_id' => $factura->id
            ]);                    
         
            DB::commit();
        }catch (Exception $e){
            DB::rollback();      
            session()->flash('msg-error', '¡¡¡ATENCIÓN!!! El registro no se grabó...');           
        } 
        session()->flash('message', 'Facturas de Viandas creadas exitosamente!!');
    }  
}
