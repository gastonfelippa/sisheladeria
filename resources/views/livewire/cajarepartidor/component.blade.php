<div class="row layout-top-spacing justify-content-center">	
    <div class="col-sm-12 col-md-6 layout-spacing"> 		
		<div class="widget-content-area br-4">
			<div class="widget-one">
                <div class="row col-12">
                    <div class="col-sm-12 col-md-6">
                        <div class="row mb-1">
                            <div class="col-8">
                                <b><span class="badge badge-warning mr-1" 
                                onclick="openModal(1)" >...</span>Caja Inicial..........$</b>
                            </div>
                            <div class="col-4 text-right">
                                <b>{{number_format($totalCI,2)}}</b>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-8">
                                <b><span class="badge badge-light mr-1">...</span>Total Cobranzas.$</b> 
                            </div>
                            <div class="col-4 text-right">
                                <b>{{number_format($totalCobrado,2)}}</b>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-8">
                                <b><span class="badge badge-warning mr-1" 
                                onclick="openModal(0)" >...</span>Total de Gastos..$</b>
                            </div>
                            <div class="col-4 text-right">
                                <b>-{{number_format($totalGastos,2)}}</b>
                            </div>                        
                        </div>                        
                        <div class="row" style="color: #ff7f26">
                            <div class="col-8">
                                <b><span class="badge badge-light mr-1">...</span>CAJA FINAL..........$</b>
                            </div>
                            <div class="col-4 text-right">
                                <b>{{number_format($totalCF,2)}}</b>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <label>Caja Repartidor</label>                      
                        @if($info->count() > 0)
                        <span class="badge badge-primary ml-4" 
                            onclick="CobrarTodas('{{$repartidor}}','{{$nomRep}}')">Cobrar Todas</span>
                        @endif    
                        <select wire:model="repartidor" class="form-control form-control-sm text-center">
                            <option value="Elegir">Elegir</option>
                            @foreach($empleados as $t)
                            <option value="{{ $t->id }}">
                                {{$t->apellido}} {{$t->name}}                        
                            </option> 
                            @endforeach                               
                        </select>	
                    </div>
                </div>
				@include('common.alerts')
				<div class="table-resposive scroll">
					<table class="table table-hover table-checkable table-sm">
						<thead>
							<tr>
								<th class="text-center">CLIENTE</th>
								<th class="text-center">IMPORTE</th>
                                @can('Facturas_edit_item')
								<th class="text-center">ACCIONES</th>
                                @endcan
							</tr>
						</thead>
						<tbody>
							@foreach($info as $r)
							<tr>
								<td class="text-left">{{$r->apeCli}} {{$r->nomCli}}</td>
								<td class="text-center">{{number_format($r->importe,2)}}</td>
                                @can('Facturas_edit_item')
								<td class="text-center">
									@include('common.actionsfactura')
                                </td>  
                                @endcan                              
							</tr>
							@endforeach
						</tbody>
					</table>                   
				</div>
            </div>
            @include('livewire.cajarepartidor.modal')	
        <input type="hidden" id="id" value="0">				
		</div>
	</div>
    @if($action == 2)
    @include('livewire.cajarepartidor.detalle')		
    @endif    
</div>

<style type="text/css" scoped>
.scroll{
    position: relative;
    height: 250px;
    margin-top: .5rem;
    overflow: auto;
}
.scrollmodal{
    position: relative;
    height: 130px;
    margin-top: .5rem;
    overflow: auto;
}
</style>



<script>
 	function Confirm(id)
    {
       let me = this
       swal({
        title: 'CONFIRMAR',
        text: '¿DESEAS ELIMINAR EL REGISTRO?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: false
    },
		function() {
			window.livewire.emit('deleteRow', id)    
			toastr.success('info', 'Registro eliminado con éxito')
			swal.close()   
        })
    }
 	function ConfirmGastoIngreso(id)
    {
       let me = this
       swal({
        title: 'CONFIRMAR',
        text: '¿DESEAS ELIMINAR EL REGISTRO si?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: false
    },
		function() {
			window.livewire.emit('destroyGastoIngreso', id)    
			toastr.success('info', 'Registro eliminado con éxito')
			swal.close()   
        })
    }

    function ConfirmDel(id)
    {
    	let me = this
    	swal({
        title: 'CONFIRMAR',
        text: '¿DESEAS ELIMINAR EL REGISTRO?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: false
    },
		function() {
			window.livewire.emit('deleteRowDel', id)    
			toastr.success('info', 'Registro eliminado con éxito')
			swal.close()   
        })
    }

    function Cobrar(id)
    {
       let me = this
       swal({
        title: 'CONFIRMAR',
        text: '¿DESEAS COBRAR LA FACTURA?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: false
    },
		function() {
			window.livewire.emit('cobrarFactura', id)    
			toastr.success('info', 'Factura cobrada con éxito')
			swal.close()   
        })
    }    

    function CobrarTodas(repId, nomRep)
    {
       let me = this
        swal({
        title: 'CONFIRMAR',
        text: '¿DESEAS COBRAR TODAS LAS FACTURAS PENDIENTES DEL REPARTIDOR \n'+ nomRep +'?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: false
    },
		function() {
			window.livewire.emit('cobrarTodas', repId)    
			toastr.success('info', 'Facturas cobradas con éxito')
			swal.close()   
        })
    } 
    
	function setfocus($id) {
        document.getElementById($id).focus();
    }

    function edit(row)
    {
        var info = JSON.parse(row)
        $('#id').val(info.id)
        $('#importe').val(info.importe)
        if($('#concepto').is(':visible')){
            $('#gasto').val(info.gasto_id)
            $('.modal-title').text('Editar Gastos')
        }else $('.modal-title').text('Editar Caja')
    }
    
    function openModal(caja)
    {
        $('#id').val(0)
        $('#importe').val('')
        $('#gasto').val('Elegir')
        if(caja == 1){
            $('#concepto').hide()
            $('#modalGastos').hide()
            $('#modalCaja').show()
            $('.modal-title').text('Agregar Caja')
        }else{
            $('#modalCaja').hide() 
            $('#concepto').show()  
            $('#modalGastos').show()
            $('.modal-title').text('Agregar Gastos')
        }
        $('#modalCajaRep').modal('show')
    } 

    function save(caja)
    {
        let cajaGasto = 1     //indica si es mov de caja o gasto del lado del servidor

        if($.trim($('#importe').val()) == '')
        {
            toastr.error('Ingresa un importe válido')
            return;
        }
        if($('#concepto').is(':visible')){
            if($('#gasto option:selected').val() == 'Elegir')
            {
                toastr.error('Elige una opción válida para el Gasto')
                return;
            }
            cajaGasto = 0
        }
        var data = JSON.stringify({
            'id'        : $('#id').val(),
            'importe'   : $('#importe').val(),            
            'gasto'     : $('#gasto option:selected').val(),
            'cajaGasto' : cajaGasto    //indica si es mov de caja o gasto del lado del servidor
        });
        window.livewire.emit('grabarCajaModal', data)
    }           

    document.addEventListener('DOMContentLoaded', function(){
        window.livewire.on('msgok', dataMsg => {
            $('#modalCajaRep').modal('hide')
        })
        window.livewire.on('msgerror', dataMsg => {
            $('#modalCajaRep').modal('hide')
        })
    });
</script>