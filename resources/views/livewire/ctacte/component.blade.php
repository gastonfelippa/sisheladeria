<div class="row layout-top-spacing">    
    <div class="col-sm-12 col-md-6 layout-spacing">      
    	<div class="widget-content-area">
    		<div class="widget-one">
    			<div class="row">
    				<div class="col-xl-12 text-center">
    					<h3><b>Cuenta Corriente</b> {{number_format($suma,2)}}</h3>
    				</div> 
    			</div>
				@if($search != '')  
					<div class="btn-group mb-2" role="group" aria-label="Basic mixed styles example">
						<button type="submit" wire:click="verHistorial(0)" class="btn btn-info mt-1" enabled>Ver Saldo</button>
						<button type="submit" wire:click="verHistorial(1)" class="btn btn-warning mt-1" enabled>Ver Historial</button>
						<button type="submit" class="btn btn-success mt-1" enabled>
							<a href="{{url('pdfViandas')}}" target="_blank">
							Imprimir </a>
						</button>
					</div> 
				@else  
					<div class="btn-group mb-2" role="group" aria-label="Basic mixed styles example">
						<button type="submit" wire:click="verHistorial(0)" class="btn btn-info mt-1" disabled>Ver Saldo</button>
						<button type="submit" wire:click="verHistorial(1)" class="btn btn-warning mt-1" disabled>Ver Historial</button>
						<button type="submit" class="btn btn-success mt-1" disabled>
							<a href="{{url('pdfViandas')}}" target="_blank">
							Imprimir </a>
						</button>
					</div> 
				@endif 		
				@include('common.inputBuscarBtnNuevo', ['create' => 'Rubros_create'])
				@include('common.alerts') <!-- mensajes -->
				<div class="table-responsive scroll">
					<table class="table table-hover table-checkable table-sm">
						<thead>
							<tr>
								@if($search != '')
									<th class="text-center">FECHA</th>
								@endif
								<th class="">CLIENTE</th>
								<th class="text-left">IMPORTE</th>
								<th class="text-center">ACCIONES</th>
							</tr>
						</thead>
						<tbody>
							@foreach($info as $r)
							<tr>
								@if($search != '')
									<td class="text-center">{{\Carbon\Carbon::parse(strtotime($r->fecha))->format('d-m-Y')}}</td>
								@endif()
								<td>{{$r->apellido}}, {{$r->nombre}}</td>

								@if($r->imp_factura == 1)
									<td class="text-right" style="color:red;width: 80px;">
									@if($verHistorial == 1) - @endif {{number_format($r->importe,2)}}</td>
								@else
									<td class="text-right" style="color:green">{{number_format($r->importe,2)}}</td>
								@endif
								
								<td class="text-center">
									@include('common.actions', ['edit' => 'Rubros_edit', 'destroy' => 'Rubros_destroy']) <!-- botons editar y eliminar -->
								</td> 
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
    	</div> 
    </div>
	@can('Rubros_create')
	<div class="col-sm-12 col-md-6 layout-spacing">
		<div class="widget-content-area">
            <div class="widget-one">
                <h5>
                    <b>@if($selected_id ==0) Nuevo Cobro  @else Editar Recibo @endif  </b>
                </h5>
                @include('common.messages')
                <div class="row">
                    <div class="form-group col-12 col-md-5 ">
                        <label >Cliente</label>
                        <div class="input-group">
                            <select wire:model="cliente" class="form-control text-center">
                                <option value="Elegir">Elegir</option>
                                @foreach($clientes as $c)
                                <option value="{{ $c->id }}">
                                    {{$c->apellido}}, {{$c->nombre}}
                                </option>                                       
                                @endforeach                              
                            </select>			               
                        </div>
                    </div>
                    <div class="form-group col-md-3 col-sm-12">
                        <label >Importe</label>
                        <input wire:model.lazy="importeCobrado" type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label >Forma de Pago</label>
                        <select wire:model="f_de_pago" class="form-control text-center">
                            <option value="efectivo">Efectivo</option>
                            <option value="cheque">Cheque</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>
                </div>
				<div class="row mb-3">
                    <div class="col-12">
                        <textarea rows="2" class="md-textarea form-control" wire:model="comentario" placeholder="Agrega un comentario..."></textarea>
                    </div>   
                </div>   
					@include('common.btnCancelarGuardar')
            </div>
        </div>	
	</div>
	@endcan
</div>

<style type="text/css" scoped>
.scroll{
    position: relative;
    height: 240px;
    margin-top: .5rem;
    overflow: auto;
}
</style>

<script type="text/javascript">
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
    		window.livewire.emit('deleteRow', id)    //emitimos evento deleteRow
    		toastr.success('info', 'Registro eliminado con éxito') //mostramos mensaje de confirmación 
    		swal.close()   //cerramos la modal
    	})
    }
    window.onload = function() {
        document.getElementById("search").focus();
    }
    function setfocus($id) {
        document.getElementById($id).focus();
    }
</script>