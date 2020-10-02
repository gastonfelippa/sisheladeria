<div class="row layout-top-spacing">	
    <div class="col-sm-12 col-md-6 layout-spacing"> 		
		<div class="widget-content-area br-4">
			<div class="widget-one">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <h3>Factura N°: {{$id_factura}}</h3>
                    </div>
                    <div class="col-sm-12 col-md-7 text-center">
                        <h3 class="bg-danger">Total : $ {{number_format($total,2)}}</h3> 
                    </div>
                </div>  
                <div class="row">
                    <div class="col-sm-6 col-md-4">
                        <h6>Fecha {{\Carbon\Carbon::now()->format('d-m-Y')}} </h6>
                    </div>
                    <div class="col-sm-6 col-md-8 text-right">
                        <button type="button" wire:click="dejar_pendiente()" onclick="setfocus('cantidad')" 
                            class="btn btn-dark">
                            Dejar Pendiente   
                        </button>
                        <button type="button" wire:click="terminar_factura()" onclick="setfocus('cantidad')" 
                            class="btn btn-primary">
                            Cobrar   
                        </button>
                        <button type="button" class="btn btn-success">
                            <a href="{{url('pdfFactDel',array($id_factura))}}">
                            Imprimir</a>
                        </button>
                    </div>
                </div>
            @if($grabar_encabezado)
                <form> 
                    <div class="row mt-1">
                        <div class="form-group col-md-5 col-sm-12 ">                       
                            <label>
                                Cliente
                                @if($habilitar_botones)
                                    <span class="badge badge-primary ml-4" 
                                    wire:click.prevent="grabarModEncabezado({{$id_factura}})">Grabar</span>
                                    <span class="badge badge-dark" 
                                    wire:click.prevent="cancelarModEncabezado()">Cancelar</span>
                                @else                               
                                    <span class="badge badge-primary ml-4" >
                                    <a href="{{ url('clientes') }}" style="color: white">Agregar</a></span>                              
                                @endif
                            </label>   
                            <select id="comboCliente" wire:model="cliente" class="form-control form-control-sm text-center">
                                <option value="Elegir">Elegir</option>
                                @foreach($clientes as $t)
                                    <option value="{{ $t->id }}">
                                        {{$t->nombre}}                         
                                    </option> 
                                @endforeach                               
                            </select>                      		               
                        </div> 
                        <div class="form-group col-md-4 col-sm-12">
                            <label >Dirección</label> 
                            <input wire:model="dirCliente" type="text" class="form-control form-control-sm" disabled>
                        </div> 
                        <div class="form-group col-md-3 col-sm-12 ">                          
                            <label >Repartidor</label>
                            <select id="comboRepartidor" wire:model="empleado" class="form-control form-control-sm text-center">
                                <option value="Elegir" onclick="setfocus('cantidad)">Elegir</option>
                                 @foreach($empleados as $t)
                                    <option value="{{ $t->id }}" onclick="setfocus('cantidad')">
                                        {{$t->nombre}}                         
                                    </option> 
                                @endforeach                               
                            </select>                   
                        </div>  
                    </div>
                </form>
            @else
                <div class="row mt-2">
                    <div class="col-sm-7">
                        <h6>Cliente:  {{$encabezado[0]->nomCli}}</h6>
                        <h6>Dirección:  {{$encabezado[0]->direccion}}</h6>
                        <h6>Repartidor:  {{$encabezado[0]->nomRep}}</h6>
                    </div>
                    <div class="col-sm-5 mt-4">
                        <span class="badge badge-primary ml-4"
                            wire:click.prevent="modificarEncabezado()">Modificar Cli/Rep</span>
                    </div>
                </div>
            @endif
			@include('common.alerts')
				<div class="table-responsive scroll">
					<table class="table table-hover table-checkable table-sm mb-4">
						<thead>
							<tr>
								<th class="text-center">CANTIDAD</th>
								<th class="text-center">DESCRIPCIÓN</th>
								<th class="text-center">PRECIO UNITARIO</th>
								<th class="text-center">IMPORTE</th>
								<th class="text-center">ACCIONES</th>
							</tr>
						</thead>
						<tbody>
							@foreach($info as $r)
							<tr class="table-danger">
								<td class="text-center">{{number_format($r->cantidad,0)}}</td>
								<td class="text-left">{{$r->producto}}</td>
								<td class="text-right">{{$r->precio}}</td>
								<td class="text-right">{{number_format($r->importe,2)}}</td>
								<td class="text-center">
									@include('common.actions')
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>                   
				</div>
			</div>			
		</div>
	</div>
    <div class="row col-sm-12 col-md-6 layout-spacing ml-0">
        <div class="widget-content-area">
            <div class="widget-one">
                <h5>
                    <b>@if($selected_id ==0) Agregar Producto  @else Editar Producto @endif  </b>
                </h5>
                <form>
                    @include('common.messages')    
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-2">
                            <label >Cantidad</label>
                            <input id="cantidad" wire:model.lazy="cantidad" onclick.keydown.enter="setfocus('productos')" type="text" 
                                class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <label>Productos</label><span class="badge badge-primary ml-4" >
                                    <a href="{{ url('productos') }}" style="color: white">Agregar</a></span>  
                            <select id="producto" wire:model="producto" class="form-control form-control-sm text-center">
                                <option value="Elegir" >Elegir</option>
                                @foreach($productos as $t)
                                <option value="{{ $t->id }}" wire:click="buscarProducto({{$t->id}})"
                                    wire:tab="buscarProducto({{$t->id}})">
                                    {{$t->descripcion}}                         
                                </option> 
                                @endforeach                               
                            </select>			               
                        </div>            
                        <div class="form-group col-sm-12 col-md-4">
                            <label >Precio Unitario</label>
                            <input wire:model="precio" type="text" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-12 mt-1 text-left">
                            <button type="button" wire:click="resetInput()" onclick="setfocus('cantidad')" class="btn btn-dark mr-1">
                                <i class="mbri-left"></i> Cancelar
                            </button>
                            <button type="button" wire:click="StoreOrUpdate()" onclick="setfocus('cantidad')" class="btn btn-primary ml-2">
                                <i class="mbri-success"></i> Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="widget-content-area mt-3">
            <div class="widget-one"> 
                <div class="row m-1 rounded">
                    <img src="images/helados.jpg" class="img-fluid" 
                        alt="Responsive image" style="height:150px;width:555px">
                </div>
            </div>
        </div>         
    </div>   
</div>

<style type="text/css" scoped>
.scroll{
    position: relative;
    height: 200px;
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
			console.log('ID', id);
			window.livewire.emit('deleteRow', id)    
			toastr.success('info', 'Registro eliminado con éxito')
			swal.close()   
        })
    }
    function buscarProducto(id)
    { 
        window.livewire.emit(buscarProducto(id))
    }
    // function buscarDomicilio(id)
    // { 
    //     console.log('ID', id);
    //     window.livewire.emit(buscarDomicilio(id))
    // }
    window.onload = function() {
        document.getElementById("cantidad").focus();
    }
    function setfocus($id) {
        document.getElementById($id).focus();
    }
    function habilitarCombos(){        
        $('#comboCliente').removeAttr('disabled');
        $('#comboRepartidor').removeAttr('disabled');        
    };

</script>