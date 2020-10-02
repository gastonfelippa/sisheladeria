<div class="modal fade" id="modalCajaRep" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            </div>       
            <div class="modal-body">
                <div class="widget-content-area">
                    <div class="widget-one">
                        <form>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>Importe</label>
                                    <input id="importe" type="text" class="form-control text-center" value="0" placeholder="...">                     
                                </div>                        
                                <div id="concepto" class="form-group col-lg-6 col-md-6 col-sm-12">
                                    <label>Concepto</label>
                                    <select id="gasto" class="form-control text-center">
                                        <option value="Elegir">Elegir</option>
                                        @foreach($gastos as $g)
                                            <option value="{{ $g->id }}">
                                                {{ $g->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                        
                        </form>
                    
                        <div id="modalCaja" class="table-responsive scrollmodal">
                            <table class="table table-hover table-checkable table-sm mb-4">
                                <thead>
                                    <tr>
                                        <th class="text-center">FECHA/HORA</th>
                                        <th class="text-center">IMPORTE</th>
                                        <th class="text-center">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($infoCaja as $r)
                                    <tr class="table-danger">
                                        <td class="text-center">{{$r->created_at}}</td>
                                        <td class="text-right">{{number_format($r->importe,2)}}</td>
                                        <td class="text-center">
                                            <ul class="table-controls">
                                                <li>
                                                    <a href="javascript:void(0);" 
                                                    onclick="edit('{{$r}}')" 
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"          		
                                                    onclick="Confirm('{{$r->id}}')"
                                                    data-toggle="tooltip" data-dismiss="modal" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>                   
                        </div>

                        <div id="modalGastos" class="table-responsive scrollmodal">
                            <table class="table table-hover table-checkable table-sm mb-4">
                                <thead>
                                    <tr>
                                        <th class="text-center">CONCEPTO</th>
                                        <th class="text-center">IMPORTE</th>
                                        <th class="text-center">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($infoGastos as $r)
                                    <tr class="table-danger">
                                        <td class="text-left">{{$r->descripcion}}</td>
                                        <td class="text-right">{{number_format($r->importe,2)}}</td>
                                        <td class="text-center">
                                            <ul class="table-controls">
                                                <li>
                                                    <a href="javascript:void(0);" 
                                                    onclick="edit('{{$r}}')" 
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"          		
                                                    onclick="Confirm('{{$r->id}}')"
                                                    data-toggle="tooltip" data-dismiss="modal" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>                   
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-dark" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>
                <button class="btn btn-primary" data-dismiss="modal" type="button" onclick="save(1)">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        document.getElementById("importe").focus();
    }
</script>