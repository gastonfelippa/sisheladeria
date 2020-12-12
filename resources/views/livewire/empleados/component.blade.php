<div class="row layout-top-spacing">    
    <div class="col-sm-12 col-md-6 layout-spacing">      
    	<div class="widget-content-area">
            <div class="widget-one">
    			<div class="row">
    				<div class="col-xl-12 text-center">
    					<h3><b>Empleados</b></h3>
    				</div> 
    			</div>    		
                @include('common.inputBuscarBtnNuevo', ['create' => 'Empleados_create'])
                @include('common.alerts') <!-- mensajes -->
                <div class="table-resposive scroll">
                    <table class="table table-hover table-checkable table-sm">
                        <thead>
                            <tr>
                                <th class="">NOMBRE</th>
                                <th class="">DIRECCIÓN</th>
                                <th class="">TELÉFONO</th>
                                <!-- <th class="">OCUPACIÓN</th>
                                <th class="">FECHA DE NAC.</th>
                                <th class="">FECHA DE INGRESO</th> -->
                                <th class="text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($info as $r) <!-- iteración para llenar la tabla-->
                            <tr>
                                <td>{{$r->nombre}}</td>
                                <td>{{$r->direccion}}</td>
                                <td>{{$r->telefono}}</td>
                            <!-- <td>{{$r->ocupacion}}</td>                    
                                <td>{{$r->fecha_nac}}</td>
                                <td>{{$r->fecha_ingreso}}</td> -->
                                <td class="text-center">
                                     @include('common.actions', ['edit' => 'Empleados_edit', 'destroy' => 'Empleados_destroy']) <!--botones editar y eliminar -->            
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    	</div> 
    </div>
    @can('Empleados_create')
	<div class="col-sm-12 col-md-6 layout-spacing">
		<div class="widget-content-area">
            <div class="widget-one">
                <h5>
                    <b>@if($selected_id ==0) Crear Nuevo Empleado  @else Editar Empleado @endif  </b>
                </h5>
                @include('common.messages')
                <div class="row">                               
                    <div class="col-12 layout-spacing">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></span>
                            </div>
                            <input id="nombre" type="text" class="form-control text-uppercase"  placeholder="Apellido y Nombre del Empleado" wire:model.lazy="nombre">
                        </div>
                    </div>
                </div>
                <div class="row">                               
                    <div class="col-12 layout-spacing">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></span>
                            </div>
                            <input type="text" class="form-control text-capitalize" placeholder=Dirección wire:model.lazy="direccion">
                        </div>
                    </div>
                </div>
                <div class="row">                               
                    <div class="col-12 layout-spacing">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></span>
                            </div>
                            <input type="text" class="form-control" placeholder=Teléfono wire:model.lazy="telefono">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-5">
                        <label >Ocupación</label>
                        <select wire:model="ocupacion" class="form-control text-left">
                            <option value="ATENCIÓN/PÚBLICO">ATENCIÓN AL PÚBLICO</option>
                            <option value="CAJERO/A">CAJERO/A</option>
                            <option value="REPARTIDOR/A">REPARTIDOR/A</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label>Fecha Nacimiento</label>
                        <input wire:model.lazy="fecha_nac" type="text" class="form-control flatpickr flatpickr-input active"
                            placeholder="Haz click">                       
                    </div>
                    <div class="form-group col-sm-12 col-md-3">
                        <label>Fecha Ingreso</label>
                        <input wire:model.lazy="fecha_ingreso" type="text" class="form-control flatpickr flatpickr-input active"
                            placeholder="Haz click">                       
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
    height: 270px;
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