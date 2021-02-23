<div class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-12 layout-spacing">
                <div class="widget-content-area">
                    <div class="widget-one">
                        <h3 class="text-center">Reporte de Ventas Diarias</h3>
                        <div class="row">
                            <div class="col-sm-12 col-lg-4 col-md-4 text-left">
                                <b>Fecha de Consulta:</b> {{\Carbon\Carbon::now()->format('d-m-Y')}}
                                <br>
                                <b>Cantidad de Registros:</b> {{$cantVentas->count()}}
                                <br>
                                <b>Total de Ingresos:</b> $ {{number_format($sumaTotal,2)}}
                            </div>
                            <div class="col-sm-12 col-md-8 col-lg-8 text-right">
                                <button type="button" class="btn btn-sm btn-warning mt-4">
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
                                            <th class="text-center">CLIENTE</th>
                                            <th class="text-center">IMPORTE</th>
                                            <th class="text-center">REPARTIDOR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($info as $r)
                                            <tr>
                                                <td class="text-center">{{$r->numero}}</td>
                                                <td class="text-center">{{$r->created_at->format('d-m-Y')}}</td>
                                                <td class="text-left">{{$r->apeCli}} {{$r->nomCli}}</td>
                                                <td class="text-center">{{number_format($r->importe,2)}}</td>
                                                <td class="text-left">{{$r->apeRep}} {{$r->apeRep}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                     <!-- <tfoot>
                                        <tr>
                                                            colspan ocupa una cant determinada de columnas
                                            <th class="text-right" colspan="10">SUMA IMPORTES:</th>
                                            <th class="text-center" colspan="2">$ {{number_format($sumaTotal,2)}}</th>
                                        </tr>
                                    </tfoot> -->
                                </table>
                                {{$info->links()}}
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>    
</div>
