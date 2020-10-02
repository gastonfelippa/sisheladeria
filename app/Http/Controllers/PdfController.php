<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use Livewire\Component;
use App\Factura;
use App\Cliente;
use App\Producto;
use App\DetFactura;
use PDF;
use DB;

class PdfController extends Controller
{
    public function PDF(){
        $pdf = PDF::loadView('prueba');
        return $pdf->stream('prueba.pdf');
    }
    public function PDFFacturas(){
        $clientes  = Cliente::all();
   
        $info = Factura::leftjoin('clientes as c','c.id','facturas.cliente_id')
            ->leftjoin('empleados as r','r.id','facturas.repartidor_id')
            ->select('facturas.*', 'c.nombre as nomcli', 'r.nombre as nomrep', DB::RAW("'' as total"))
            ->where('facturas.estado','like','PAGADA')
            ->where('facturas.repartidor_id', 'like', '1')
            ->orderBy('facturas.id', 'asc')->get(); 

        $pdf = PDF::loadView('livewire.pdf.pdfFacturas', compact('info'));
        return $pdf->stream('facturas.pdf');
    }

    public function PDFFactDel($id){
        $clientes  = Cliente::all();
        $productos = Producto::all();
      
        $infoDetalle = DetFactura::leftjoin('facturas as f','f.id','detfacturas.factura_id')
          ->leftjoin('productos as p','p.id','detfacturas.producto_id')
          ->select('detfacturas.*', 'p.descripcion as producto', DB::RAW("'' as importe"))
          ->where('detfacturas.factura_id', $id)
          ->orderBy('detfacturas.id', 'asc')->get(); 

      $this->importeFactura = 0;  

      foreach ($infoDetalle as $i)
      {
          $i->importe=$i->cantidad * $i->precio;
          $this->importeFactura += $i->importe;
      }
        $info = Factura::leftjoin('clientes as c','c.id','facturas.cliente_id')
            ->leftjoin('empleados as r','r.id','facturas.repartidor_id')
            ->select('facturas.*', 'c.nombre as nomcli', 'c.direccion as dircli')
            ->where('facturas.id','like',$id)->get();

        $pdf = PDF::loadView('livewire.pdf.pdfFactDel', compact(['infoDetalle','info']));
        return $pdf->stream('fact.pdf');
    }
}
