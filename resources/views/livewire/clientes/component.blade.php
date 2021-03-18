<div class="row layout-top-spacing"> 
    @if($action == 1)   
    <div class="col-12 layout-spacing">      
    	<div class="widget-content-area">
            <div class="widget-one">
    			<div class="row">
    				<div class="col-xl-12 text-center">
    					<h3><b>Clientes</b></h3>
    				</div> 
    			</div>    		
                @include('common.inputBuscarBtnNuevo', ['create' => 'Clientes_create'])
                @include('common.alerts') <!-- mensajes -->
                <div class="table-resposive scroll">
                    <table class="table table-hover table-checkable table-sm">
                        <thead>
                            <tr>
                                <th class="">APELLIDO Y NOMBRES</th>
                                <th class="">DIRECCIÓN</th>
                                <th class="text-center">TELÉFONO</th>
                                <th class="text-center">CLIENTE/VIANDA</th>
                                <th class="text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($info as $r)
                            <tr>
                                <td >{{$r->apellido}}, {{$r->nombre}}</td>
                                <td>{{$r->calle}} {{$r->numero}} - {{$r->localidad}}</td>
                                <td class="text-center">{{$r->telefono}}</td>
                                @if($r->vianda == 1)
                                    <td class="text-center">                                 
                                        <a href="javascript:void(0);"
                                        wire:click="verViandas({{$r->id}}, 3)"  
                                        data-toggle="tooltip" data-placement="top" title="Ver">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-success"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>                                 
                                    </td>
                                @else
                                    <td class="text-center">
                                        <a href="javascript:void(0);"   
                                        data-toggle="tooltip" data-placement="top">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off text-danger"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                    </td>
                                @endif
                                <td class="text-center">
                                    @include('common.actions', ['edit' => 'Clientes_edit', 'destroy' => 'Clientes_destroy']) <!--botones editar y eliminar -->            
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    	</div> 
    </div>
    @elseif($action == 2)
        @can('Clientes_create')
            @include('livewire.clientes.form')    
            @include('livewire.clientes.modal')     
        @endcan
    @elseif($action == 3)    
        @include('livewire.clientes.viandas') 
    @endif
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

    function openModal()
    {        
        $('#localidad').val('')
        $('#provincia').val('Elegir')
        $('#modalAddLocalidad').modal('show')
	}
	function save()
    {
        if($('#localidad').val() == '')
        {
            toastr.error('El campo Localidad no puede estar vacío')
            return;
        }
        if($('#provincia option:selected').val() == 'Elegir')
        {
            toastr.error('Elige una opción válida para la Provincia')
            return;
        }
        var data = JSON.stringify({
            'localidad': $('#localidad').val(),
            'provincia_id'  : $('#provincia option:selected').val()
        });

        $('#modalAddLocalidad').modal('hide')
        window.livewire.emit('createFromModal', data)
    } 

    window.onload = function() {
        document.getElementById("search").focus();
    }
    function setfocus($id) {
        document.getElementById($id).focus();
    }
</script>