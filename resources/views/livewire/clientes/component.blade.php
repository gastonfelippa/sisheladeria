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
                                <th class="text-center">APELLIDO Y NOMBRES</th>
                                <th class="">DIRECCIÓN</th>
                                <th class="text-center">TELÉFONO</th>
                                <th class="text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($info as $r)
                            <tr>
                                <td >{{$r->apellido}}, {{$r->nombre}}</td>
                                <td>{{$r->calle}} {{$r->numero}}</td>
                                <td class="text-center">{{$r->telefono}}</td>
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
    @elseif($action > 1)
    @can('Clientes_create')
    @include('livewire.clientes.form')
    @endcan
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
    window.onload = function() {
        document.getElementById("search").focus();
    }
    function setfocus($id) {
        document.getElementById($id).focus();
    }
</script>