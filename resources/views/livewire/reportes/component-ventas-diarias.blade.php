<div class="row layout-top-spacing">
    <div class="col-12 layout-spacing">
        <div class="widget-content-area">
            <div class="widget-one">
                <h3 class="text-center">Reporte de Ventas Diarias</h3>
                <div class="row">
                    <div class="col-sm-12 col-md-3 text-left">
                        <b>Fecha de Consulta:</b> {{\Carbon\Carbon::now()->format('d-m-Y')}}
                        <br>
                        <b>Cantidad de Registros:</b> {{$info->count()}}
                        <br>
                        @if($estado == 1)
                        <b>Total de Ingresos:</b> $ {{number_format($sumaTotal,2)}}
                        @endif
                        @if($estado == 2)
                        <b>Total Contado:</b> $ {{number_format($sumaTotal,2)}}
                        @endif
                        @if($estado == 3)
                        <b>Total Cuenta Corriente:</b> $ {{number_format($sumaTotal,2)}}
                        @endif
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></span>
                            </div>
                            <input type="text" wire:model="search" class="form-control form-control-sm" placeholder="Buscar.." aria-label="notification" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="1" wire:model="estado" checked>
                            <label class="form-check-label">Todas</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="2" wire:model="estado" >
                            <label class="form-check-label">Contado</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="3" wire:model="estado">
                            <label class="form-check-label">Cta. Cte.</label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <button type="button" class="btn btn-block btn-warning">
                        <a href="{{url('pdfFacturas')}}" target="_blank">
                            IMPRIMIR </a>   
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-1"></div>
                    <div class="table-responsive mt-3 table-sm col-lg-10">
                        <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                            <thead>
                                <tr>
                                    <th class="text-center">FACT N°</th>
                                    <th class="text-center">FECHA</th>
                                    @if($estado ==3)
                                    <th class="text-center">CLIENTE</th>
                                    @endif
                                    <th class="text-center">IMPORTE</th>
                                    @if($estado ==3)
                                    <th class="text-center">REPARTIDOR</th>
                                    @endif
                                    <th class="text-center">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($info as $r)
                                <tr>
                                    <td class="text-center">{{$r->numero}}</td>
                                    <td class="text-center">{{$r->created_at->format('d-m-Y')}}</td>
                                    @if($estado ==3)                                 
                                    <td class="text-left">{{$r->apeCli}} {{$r->nomCli}}</td> 
                                    @endif                                    
                                    <td class="text-center">{{number_format($r->importe,2)}}</td> 
                                    @if($estado ==3)                                 
                                    <td class="text-left">{{$r->apeRep}} {{$r->nomRep}}</td> 
                                    @endif                                
                                    <td class="text-center">
                                        @include('common.actions', ['edit' => 'Proveedores_edit', 'destroy' => 'Proveedores_destroy']) <!--botones editar y eliminar -->            
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$info->links()}}
                    </div>
                </div>               
            </div>
        </div>
    </div>    
</div>
