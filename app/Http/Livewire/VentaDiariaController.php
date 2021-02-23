<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use\App\Factura;
use\Carbon\Carbon;

class VentaDiariaController extends Component
{
    use WithPagination;

    public $fecha_ini, $fecha_fin, $pagination = 5;

    public function render()
    {
        $cantVentas = Factura::leftjoin('clientes as c', 'c.id', 'facturas.cliente_id')
            ->leftjoin('empleados as e', 'e.id', 'facturas.repartidor_id')
            ->select('facturas.*', 'c.nombre as nomCli', 'c.apellido as apeCli', 
                     'e.nombre as nomRep', 'e.apellido as apeRep')
            ->whereDate('facturas.created_at', Carbon::today())
            ->where('facturas.estado', 'PAGADA')
            ->orderBy('id', 'desc');
            // ->where('facturas.cliente_id', '<>', null)
        
        $ventas = Factura::leftjoin('clientes as c', 'c.id', 'facturas.cliente_id')
                ->leftjoin('empleados as e', 'e.id', 'facturas.repartidor_id')
                ->select('facturas.*', 'c.nombre as nomCli', 'c.apellido as apeCli', 
                     'e.nombre as nomRep', 'e.apellido as apeRep')
                ->whereDate('facturas.created_at', Carbon::today())
                ->where('facturas.estado', 'PAGADA')
                ->orderBy('id', 'desc')
                ->paginate($this->pagination);
                // ->where('facturas.cliente_id', '<>', null)

        $total = Factura::whereDate('facturas.created_at', Carbon::today())->where('estado', 'PAGADA')->sum('importe');
//dd($cantVentas);
        return view('livewire.reportes.component-ventas-diarias',
        [   'info'     => $ventas,
            'sumaTotal'=> $total,
            'cantVentas'=> $cantVentas
        ]);
    }
}
