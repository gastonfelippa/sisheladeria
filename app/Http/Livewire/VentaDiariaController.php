<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use\App\Factura;
use\Carbon\Carbon;

class VentaDiariaController extends Component
{
    use WithPagination;

    public $pagination = 5, $comercioId, $estado = 1;

    public function render()
    {
         //busca el comercio que está en sesión
        $this->comercioId = session('idComercio');

        switch ($this->estado) {
            case '1': //todas
                $info = Factura::whereDate('facturas.created_at', Carbon::today())
                            ->where('facturas.comercio_id', $this->comercioId)
                            ->orderBy('facturas.id', 'desc')
                            ->paginate($this->pagination);
                break;
            case '2': //contado 
                $info = Factura::whereDate('created_at', Carbon::today())
                            ->where('estado', 'PAGADA')
                            ->where('comercio_id', $this->comercioId)
                            ->orderBy('id', 'desc')
                            ->paginate($this->pagination);
                break;
            case '3': //ctacte 
                $info = Factura::join('clientes as c', 'c.id', 'facturas.cliente_id')
                            ->join('users as u', 'u.id', 'facturas.repartidor_id')
                            ->select('facturas.*', 'c.nombre as nomCli', 'c.apellido as apeCli', 
                                    'u.name as nomRep', 'u.apellido as apeRep')
                            ->whereDate('facturas.created_at', Carbon::today())
                            ->where('facturas.estado', 'CTACTE')
                            ->where('facturas.comercio_id', $this->comercioId)
                            ->orderBy('facturas.id', 'desc')
                            ->paginate($this->pagination);
                break;
            default:
        }

        $total = 0;
        foreach($info as $i){
            $total += $i->importe;
        }

        return view('livewire.reportes.component-ventas-diarias',
            [   'info'     => $info,
                'sumaTotal'=> $total
            ]);
    }
}
