<div class="row layout-top-spacing">    
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
    	@if($action == 1)         
    	<div class="widget-content-area br-4">
    		<div class="widget-header">
    			<div class="row">
    				<div class="col-xl-12 text-center">
    					<h5><b>Usuarios de Sistema</b></h5>
    				</div> 
    			</div>
    		</div>
    		@include('common.search') <!-- búsqueda y botón para nuevos registros -->
    		<div class="table-responsive">
    			<table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
    				<thead>
    					<tr>                                                   
    						<th class="">NOMBRE</th>
    						<th class="">APELLIDO</th>
    						<th class="">TELÉFONO</th>
    						<th class="">CELULAR</th>
    						<th class="">DIRECCIÓN</th>
    						<th class="">EMAIL</th>
    						<!-- <th class="">TIPO</th> -->
    						<th class="text-center">ACCIONES</th>
    					</tr>
    				</thead>
    				<tbody>
    					@foreach($info as $r) <!-- iteración para llenar la tabla-->
    					<tr>
    						<td><p class="mb-0">{{$r->nombre}}</p></td>
    						<td>{{$r->apellido}}</td>
    						<td>{{$r->telefono}}</td>
    						<td>{{$r->movil}}</td>
    						<td>{{$r->direccion}}</td>
    						<td>{{$r->email}}</td>
    						<!-- <td>{{$r->tipo}}</td> -->
    						<td class="text-center">
    							@include('common.actions', ['edit' => 'Usuarios_edit', 'destroy' => 'Usuarios_destroy']) <!-- botons editar y eliminar -->
    						</td>
    					</tr>
    					@endforeach
    				</tbody>
    			</table>
    			{{$info->links()}} <!--paginado de tabla -->
    		</div>
    	</div>   
    	@elseif($action == 2)
    	@include('livewire.usuarios.form')		
    	@endif  
    </div>
</div>

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
    		window.livewire.emit('deleteRow', id)    //emitimos evento deleteRow
    		toastr.success('info', 'Registro eliminado con éxito') //mostramos mensaje de confirmación 
    		swal.close()   //cerramos la modal
    	})
    }
</script>
