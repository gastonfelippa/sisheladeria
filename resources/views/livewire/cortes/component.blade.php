<div class="row layout-top-spacing">    
    <div class="col-sm-12 layout-spacing">      
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
                                    <option value="{{$u->id}}">{{$u->nombre}}</option>
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
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-4 col-md-4 col-lg-4 layout-spacing">
                                <div class="color-box">
                                    <span class="cl-example text-center" style="background-color: #8dbf42; font-size: 3rem;color: white">+</span>
                                    <div class="cl-info">
                                        <h1 class="cl-title">Ventas</h1>
                                        <span>$ {{number_format($ventas,2)}}</span>
                                    </div>
                                </div>
                            </div>
                        <!-- </div>
                        <div class="row"> -->
                            <div class="col-sm-4 col-md-4 col-lg-4 layout-spacing">
                                <div class="color-box">
                                    <span class="cl-example text-center" style="background-color: #8dbf42; font-size: 3rem;color: white">+</span>
                                    <div class="cl-info">
                                        <h1 class="cl-title">Entradas</h1>
                                        <span>$ {{number_format($entradas,2)}}</span>
                                    </div>
                                </div>
                            </div>
                        <!-- </div>
                        <div class="row"> -->
                            <div class="col-sm-4 col-md-4 col-lg-4 layout-spacing">
                                <div class="color-box">
                                    <span class="cl-example text-center" style="background-color: #8dbf42; font-size: 3rem;color: white">-</span>
                                    <div class="cl-info">
                                        <h1 class="cl-title">Salidas</h1>
                                        <span>$ {{number_format($salidas,2)}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>             
                <!-- IMPRIMIR --> 
                <div class="row">             
                    <div class="col-sm-6 text-right">
                        <h4>====Balance====</h4>
                        <h2 class="mt-4">$ {{number_format($balance,2)}}</h2>
                    </div>
                    <div class="col-sm-6">
                        @if($balance > 0)
                        <button wire:click.prevent="$emit('infoToPrintCorte', 
                        {{$ventas}},{{$entradas}},{{$salidas}},{{$balance}} )" 
                        class="btn btn-outline-primary mt-5">Imprimir Corte</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
