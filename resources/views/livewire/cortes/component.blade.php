<div class="row layout-top-spacing">    
<div class="col-sm-12">      
<div class="widget-content-area">
<div class="widget-one">
    <h3 class="text-center">Corte de Caja</h3>
    <div class="row">
        <div class="col-sm-12 col-md-2 col-lg-2">
            Elige Fecha
            <div class="form-group">
                <input wire:model.lazy="fecha" type="text" class="form-control flatpickr flatpickr-input active"
                placeholder="Haz click">
            </div>
        </div>
        <div class="col-sm-12 col md-3 col-lg-3">
            <div class="form-group">Elige Operador
                <select wire:model="user" class="form-control">
                    <option value="todos">Todos</option>
                    @foreach($users as $u)
                        <option value="{{$u->id}}">{{$u->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-1 col-lg-1">
            @if($fecha != null)
                <button wire:click.prevent="Consultar()" class="btn btn-info mt-4">Consultar</button>
            @endif
        </div>
        <div class="col-sm-12 col-md-2 col-lg-2 ml-4">                   
                <button wire:click.prevent="Balance()" class="btn btn-dark mt-4">Corte de Hoy</button>                   
        </div>
    </div>
    <!-- <hr> -->
    <div class="row">
        <div class="col-sm-4 col-md-4 col-lg-3 layout-spacing">
            <div class="color-box">
                <span class="cl-example text-center" style="background-color: #394BA1; font-size: 3rem;color: white">#</span>
                <div class="cl-info">
                    <h1 class="cl-title">Caja Inicial</h1>
                    <span>$ {{number_format($cajaInicial,2)}}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-3 layout-spacing">
            <div class="color-box mb-1">
                <span class="cl-example text-center" style="background-color: #8dbf42; font-size: 3rem;color: white">+</span>
                <div class="cl-info">
                    <h1 class="cl-title">Ventas</h1>
                    <span>$ {{number_format($ventas,2)}}</span>
                </div>
            </div>
            <div class="color-box mb-1">
                <span class="cl-example text-center" style="background-color: #8dbf42; font-size: 3rem;color: white">+</span>
                <div class="cl-info">
                    <h1 class="cl-title">Cobros Cta Cte</h1>
                    <span>$ {{number_format($cobrosCtaCte,2)}}</span>
                </div>
            </div>
            <div class="color-box">
                <span class="cl-example text-center" style="background-color: #8dbf42; font-size: 3rem;color: white">+</span>
                <div class="cl-info">
                    <h1 class="cl-title">Otros Ingresos</h1>
                    <span>$ {{number_format($otrosIngresos,2)}}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-3 layout-spacing">
    <a href="{{ url('compras') }}" >
            <div class="color-box">
                <span class="cl-example text-center" style="background-color: #F2351F; font-size: 3rem;color: white">-</span>
                <div class="cl-info">
                    <h1 class="cl-title">Egresos</h1>
                    <span onclick="openModal()">$ {{number_format($egresos,2)}}</span>
                </div>
            </div>
    </a>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-3 layout-spacing">
            <div class="color-box">
                <span class="cl-example text-center" style="background-color: #394BA1; font-size: 3rem;color: white">#</span>
                <div class="cl-info">
                    <h1 class="cl-title">Caja Final</h1>
                       <span>$ {{number_format($cajaFinal,2)}}</span>
                </div>
            </div>
        </div>
               
</div>
</div>
</div>
</div>
