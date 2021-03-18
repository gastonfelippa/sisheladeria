<div class="row layout-top-spacing">
    <div class="col-12 layout-spacing">
    @include('common.alerts') 
        <div class="widget-content-area">
            <div class="widget-one">
                <h4 class="text-center">Viandas Diarias</h4>
                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <button type="submit" onclick="cambiarDiv(2)" class="btn btn-info mt-1">Ver Lista Cocina</button>
                        <button type="submit" class="btn btn-danger mt-1">
                            <a href="{{url('pdfViandas')}}" target="_blank">
                            Imprimir </a>
                        </button>
                </div>
                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <button type="submit" onclick="cambiarDiv(3)" class="btn btn-info mt-1">Ver Comentarios</button>
                        <button type="submit" class="btn btn-danger mt-1">
                            <a href="{{url('pdfViandas')}}" target="_blank">
                            Imprimir </a>
                        </button>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-2 col-lg-2">
                        Fecha
                        <div class="form-group"> 
                            <input id="fecha" onchange="cambiarDiv(1)" name="fecha" type="text" class="form-control flatpickr flatpickr-input" placeholder="Cambiar fecha"  autocomplete="off">
                        </div>
                    </div> 
                    <div class="col-sm-12 col-md-2 col-lg-2 text-left">
                        <button type="submit" onclick="cambiarDiv(1)" class="btn btn-info mt-1">Ver Lista Facturas</button>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 text-left">
                        <button type="submit" onclick="ConfirmGrabar()" class="btn btn-info mt-1">Grabar viandas</button>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 offset-lg-3">
                
                         <b>Fecha de Consulta:</b><span id="fechaConsulta"></span>
                        <br>
                        <b>Cantidad Viandas: </b><span id="cViandas"></span>
                        <!-- @php
                            $suma=0;
                        @endphp
                        @foreach ($info as $r)
                            @php
                            $suma+=$r->cantidad;
                            @endphp             
                        @endforeach
                        {{$suma}}                       -->
                    </div>
                </div> 

                <div class="row justify-content-center">
                        <!-- <div class="row justify-content-center"> -->
                            <div id="div1" name="div1" class="col-sm-12 col-lg-8">
                                <div class="table-resposive scroll">
                                    <table class="table table-hover table-checkable table-sm">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th class="text-center">CLIENTE</th>
                                                <th class="text-center">CANTIDAD</th>
                                                <th class="text-center">PRODUCTO</th>
                                                <th class="text-right">PR UNIT</th>
                                                <th class="text-right">IMPORTE</th>
                                                <th class="text-center">ACCION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($info as $r)
                                            <tr>
                                                <td class="text-left">
                                                    <!-- <div class="form-check"> -->
                                                        <input value="{{$r->cliente_id}}"  class="name" name="checks" type="checkbox" id="{{$r->cliente_id}}" checked>                                                
                                                    <!-- </div> -->
                                                </td>
                                                <td class="text-left">{{$r->apellido}}, {{$r->nombre}}</td>
                                                <td class="text-center">{{$r->cantidad}}</td>
                                                <td class="text-center">{{$r->descripcion}}</td>
                                                <td class="text-right">{{$r->precio_venta}}</td>
                                                <td class="text-right">{{number_format($r->importe,2)}}</td>
                                                <td class="text-center">                                                                                     
                                                    <a href="javascript:void(0);" onclick="openModal('{{$r}}')" data-toggle="tooltip" data-placement="top" title="Editar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div> 
                            <!-- </div> -->
                        </div>
                    
                        <!-- <div class="row justify-content-center"> -->
                            <div id="div2" name="div2"class="col-sm-12 col-lg-6">
                                <div  class="table-resposive scroll">
                                    <table class="table table-hover table-checkable table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center">HORA</th>
                                                <th class="text-center">CLIENTE</th>
                                                <th class="text-center">CANTIDAD</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($info as $r)
                                            <tr> 
                                                <td class="text-center">{{\Carbon\Carbon::parse($r->hora)->format('H:i')}}</td>
                                                <td class="text-left">{{$r->apellido}}, {{$r->nombre}}</td>                                        
                                                <td class="text-center">{{$r->cantidad}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            <!-- </div> -->
                        </div>   
                        <!-- <div class="row justify-content-center"> -->
                            <div id="div3" name="div3" class="col-sm-12 col-lg-6">
                                <div class="table-resposive scroll">
                                    <table class="table table-hover table-checkable table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center">CLIENTE</th>
                                                <th class="text-center">COMENTARIO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($info as $r)
                                                @if($r->comentarios != '')
                                                    <tr> 
                                                        <td class="text-left">{{$r->apellido}}, {{$r->nombre}}</td>                                        
                                                        <td class="text-center">{{$r->comentarios}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            <!-- </div> -->
                        </div>   
                </div> 
            </div>
        </div>
                @include('livewire.viandas.modal')        
    </div>
</div>
   


<style type="text/css" scoped>
.scroll{
    height: 210px;
    position: relative;
    margin-top: .5rem;
    overflow: auto;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 

<script type="text/javascript">
    function ConfirmGrabar() {
    	let me = this
    	swal({
    		title: 'CONFIRMAR',
    		text: '¿DESEAS GRABAR TODOS LOS REGISTROS?',
    		type: 'warning',
    		showCancelButton: true,
    		confirmButtonColor: '#3085d6',
    		cancelButtonColor: '#d33',
    		confirmButtonText: 'Aceptar',
    		cancelButtonText: 'Cancelar',
    		closeOnConfirm: false
    	},

    	function() {                   
            var arr = $('[name="checks"]:checked').map(function(){
                return this.value;
            }).get();               
            var data = JSON.stringify(arr);    
    		window.livewire.emit('grabar',data);
    		swal.close();
    	})
    }
        
    function openModal(row) {
        var info = JSON.parse(row)
        $('.modal-title').text('Cliente: '+ info.apellido +' '+ info.nombre)
        $('#cliente_id').val(info.cliente_id)
        $('#cliente_id').hide()
        $('#cantidad').val('')
        $('#producto').val('Elegir')
        $('#modalVianda').modal('show')
    }
    function save()
    {
        if($('#cantidad').val() == '')
        {
            toastr.error('El campo Cantidad no puede estar vacío')
            return;
        }
        if($('#producto option:selected').val() == 'Elegir')
        {
            toastr.error('Elige una opción válida para el Producto')
            return;
        }
        var data = JSON.stringify({
            'cliente_id'   : $('#cliente_id').val(),
            'cantidad'     : $('#cantidad').val(),
            'producto_id'  : $('#producto option:selected').val()
        });

        $('#modalVianda').modal('hide')
        window.livewire.emit('createFactFromModal', data)
    } 

    window.onload = function() {                
        var arr = $('[name="checks"]:checked').map(function(){            
            return this.id;           
        }).get();
            
        var total =0;
        for(var i of arr) total = parseInt(total) + parseInt(i);    
        $('#cViandas').text(total);
    
        cambiarDiv(1); 
    };

    function cambiarDiv(idButton) {     
        switch(idButton) {
            case 1:
                    div1.style.display = 'block';
                    div2.style.display = 'none';
                    div3.style.display = 'none';
                break;
            case 2:
                    div1.style.display = 'none';
                    div2.style.display = 'block';
                    div3.style.display = 'none';
                break;
            case 3:
                    div1.style.display = 'none';
                    div2.style.display = 'none';
                    div3.style.display = 'block';
                break;
            default:
        }
    }

    $(document).ready(function() {
        $('[name="checks"]').click(function() {            
            var arr = $('[name="checks"]:checked').map(function(){            
                return this.id;           
            }).get();
            
            var total =0;
            for(var i of arr) total = parseInt(total) + parseInt(i);    
            $('#cViandas').text(total);
        });
    });

    $(document).ready(function() {
        $('[name="fecha"]').change(function() {
            cambiarDiv(1); 
            var data =  $('#fecha').val();
         
            $('#fechaConsulta').text(data);  
            window.livewire.emit('cambiarFecha', data);
        });
    });

</script>






