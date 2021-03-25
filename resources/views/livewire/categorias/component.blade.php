<div class="row layout-top-spacing justify-content-center">  
	@include('common.alerts')
	@include('common.messages')
	@if($action == 1)  
    <div class="col-sm-12 col-md-6 layout-spacing">      
    	<div class="widget-content-area">
    		<div class="widget-one">
    			<div class="row">
    				<div class="col-xl-12 text-center">
    					<h3><b>Categorías</b></h3>
    				</div> 
    			</div> 
				@if($recuperar_registro == 1)
				@include('common.recuperarRegistro')
				@else  		
					@include('common.inputBuscarBtnNuevo', ['create' => 'Categorias_create'])
					<div class="table-responsive scroll">
						<table class="table table-hover table-checkable table-sm">
							<thead>
								<tr>
									<th class="text-center">DESCRIPCIÓN</th>
									<th class="text-center">MARGEN DE GANANCIA</th>
									<th class="text-center">ACCIONES</th>
								</tr>
							</thead>
							<tbody>
								@foreach($info as $r)
								<tr>
									<td>{{$r->descripcion}}</td>
									<td class="text-center">{{$r->margen}} %</td>
									<td class="text-center">
										@include('common.actions', ['edit' => 'Categorias_edit', 'destroy' => 'Categorias_destroy'])
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			</div>
    	</div> 
    </div>
	@else
	@can('Categorias_create')
	@include('livewire.categorias.form')		
	@endif
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
    		window.livewire.emit('deleteRow', id)
    		swal.close()
    	})
    }
    window.onload = function() {
        document.getElementById("search").focus();
    }
    function setfocus($id) {
        document.getElementById($id).focus();
    }
</script>