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
			
			<!-- <button type="button" onclick="openModal('{{$empleados}},{{$roles}}')" class="btn btn-dark"></button> -->
			
			@include('common.search') 
			@include('common.alerts') 
    		<div class="table-responsive scroll">
    			<table class="table table-hover table-checkable table-sm mb-2">
    				<thead>
    					<tr>                                                   
    						<th class="">APELLIDO Y NOMBRES</th>
    						<th class="">ROL</th>
    						<th class="">TELÉFONO</th>
    						<th class="">DIRECCIÓN</th>
    						<th class="">EMAIL</th>
    						<th class="text-center">ACCIONES</th>
    					</tr>
    				</thead>
    				<tbody>
    					@foreach($info as $r) <!-- iteración para llenar la tabla-->
    					<tr>
    						<td><p class="mb-0">{{$r->apellido}}, {{$r->name}}</p></td>
    						<td>{{$r->alias}}</td>
    						<td>{{$r->telefono1}}</td>
    						<td>{{$r->direccion}}</td>
    						<td>{{$r->email}}</td>
    						<td class="text-center">
								@include('common.actions', ['edit' => 'Usuarios_edit', 'destroy' => 'Usuarios_destroy']) 
								<!-- <ul class="table-controls">
									@can('Usuarios_edit')
									<li>
										<a href="javascript:void(0);" wire:click="edit({{$r->id}})" data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a>
									</li>
									@endcan
									@can('Usuarios_destroy')
									<li>
										<a href="javascript:void(0);"          		
										onclick="Confirm('{{$r->id}}')"
										data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
									</li>
									@endcan
								</ul> -->
    						</td>
    					</tr>
    					@endforeach
    				</tbody>
    			</table>
    			{{$info->links()}} <!--paginado de tabla -->
    		</div>
		</div> 
		@include('livewire.usuarios.modal')  
    	@elseif($action == 2)
    	@include('livewire.usuarios.form')		
    	@endif  
    </div>
</div>

<style type="text/css" scoped>
.scroll{
    position: relative;
    height: 250px;
    margin-top: .5rem;
    overflow: auto;
}
</style>
<script src="{{ asset('assets/js/sweetAlert.js') }}"></script>
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
	function Agregar()
	{
		Swal.fire({
			title: 'El nuevo Usuario es Empleado?',
			showDenyButton: true,
			showCancelButton: true,
			confirmButtonText: `Si`,
			denyButtonText: `No`,
			}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.isConfirmed) {
				Swal.fire({
					input: 'select',
					inputPlaceHolder: 'Empleado',
					inputValue: '@foreach($empleados as $e)
						{{$e->nombre}}@endforeach',
					inputOptions:'',

				})
			} else if (result.isDenied) {
				Swal.fire('Changes are not saved', '', 'info')
			}
		})
	}
	function openModal(empleados,roles)
    {
        $('#empleado').val('Elegir')
        $('#rol').val('Elegir')
        $('#empleados').val(empleados)
        $('#roles').val(roles)
        $('#modalRolUsuario').modal('show')
	}
	function save()
    {
        if($('#empleado option:selected').val() == 'Elegir')
        {
            toastr.error('Elige una opción válida para el Empleado')
            return;
        }
        if($('#rol option:selected').val() == 'Elegir')
        {
            toastr.error('Elige una opción válida para el Rol')
            return;
        }
        var data = JSON.stringify({
            'empleado_id': $('#empleado option:selected').val(),
            'rol_id'  : $('#rol option:selected').val()
        });

        window.livewire.emit('createFromModal', data)
    }           

    document.addEventListener('DOMContentLoaded', function(){
        window.livewire.on('msgok', dataMsg => {
            $('#modalRolUsuario').modal('hide')
        })
        window.livewire.on('msgerror', dataMsg => {
            $('#modalRolUsuario').modal('hide')
        })
    });
</script>
