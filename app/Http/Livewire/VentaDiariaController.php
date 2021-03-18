<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use\App\Factura;
use\Carbon\Carbon;

class VentaDiariaController extends Component
{
    use WithPagination;

    public $fecha_ini, $fecha_fin, $pagination = 5, $comercioId;

    public function render()
    {
         //busca el comercio que está en sesión
         $this->comercioId = session('idComercio');

        $cantVentas = Factura::leftjoin('clientes as c', 'c.id', 'facturas.cliente_id')
            ->leftjoin('empleados as e', 'e.id', 'facturas.repartidor_id')
            ->select('facturas.*', 'c.nombre as nomCli', 'c.apellido as apeCli', 
                     'e.nombre as nomRep', 'e.apellido as apeRep')
            ->whereDate('facturas.created_at', Carbon::today())
            ->where('facturas.estado', 'CTACTE')
            ->where('c.comercio_id', $this->comercioId)
            ->orderBy('id', 'desc');
            // ->where('facturas.cliente_id', '<>', null)
        
        $ventas = Factura::leftjoin('clientes as c', 'c.id', 'facturas.cliente_id')
                ->leftjoin('empleados as e', 'e.id', 'facturas.repartidor_id')
                ->select('facturas.*', 'c.nombre as nomCli', 'c.apellido as apeCli', 
                     'e.nombre as nomRep', 'e.apellido as apeRep')
                ->whereDate('facturas.created_at', Carbon::today())
                ->where('facturas.estado', 'CTACTE')
                ->where('c.comercio_id', $this->comercioId)
                ->orderBy('id', 'desc')
                ->paginate($this->pagination);
                // ->where('facturas.cliente_id', '<>', null)

        $total = Factura::whereDate('facturas.created_at', Carbon::today())
            ->where('estado', 'PAGADA')
            ->where('comercio_id', $this->comercioId)->sum('importe');

        return view('livewire.reportes.component-ventas-diarias',
        [   'info'     => $ventas,
            'sumaTotal'=> $total,
            'cantVentas'=> $cantVentas
        ]);
    }
}
