<div class="row layout-top-spacing">    
    <div class="col-sm-12 col-md-6 layout-spacing">             
        <div class="widget-content-area">
            <div class="widget-one">
                <div class="row">
                    <div class="col-xl-12 text-center">
                        <h3><b>Productos</b></h3>
                    </div> 
                </div>    		
				@include('common.inputBuscarBtnNuevo', ['create' => 'Productos_create']) 
                @include('common.alerts') <!-- mensajes -->  
                @include('livewire.productos.modal')
                <div class="table-responsive scroll">
                    <table class="table table-hover table-checkable table-sm">
                        <thead>
                            <tr>                                                   
                                <th class="">ID</th>
                                <th class="">DESCRIPCIÓN</th>
                                @can('Productos_create')
                                <th class="text-center">P/COSTO</th>
                                @endcan
                                <th class="text-center">P/VENTA</th>
                                <th class="text-center">ESTADO</th>
                                <th class="text-center">STOCK</th>
                                @can('Productos_create')
                                <th class="text-center">CATEGORIA</th>
                                <th class="text-center">ACCIONES</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($info as $r)
                            <tr>                     
                                <td class="text-center"><p class="mb-0">{{$r->codigo}}</p></td>
                                <td>{{$r->descripcion}}</td>
                                @can('Productos_create')
                                <td class="text-right">{{$r->precio_costo}}</td>
                                @endcan
                                <td class="text-right">{{$r->precio_venta}}</td>                               
                                <td class="text-center">{{$r->estado}}</td>
                                <td class="text-center">{{$r->stock}}</td>
                                @can('Productos_create')
                                <td>{{$r->rubro}}</td>
                                @endcan
                                <td class="text-center">
                                    @include('common.actions', ['edit' => 'Productos_edit', 'destroy' => 'Productos_destroy'])
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
    </div>
    @can('Productos_create')
    <div class="col-sm-12 col-md-6 layout-spacing"> 
        <div class="widget-content-area ">
            <div class="widget-one">
                <h5>
                    <b>@if($selected_id ==0) Crear Nuevo Producto  @else Editar Producto @endif  </b>
                </h5>
                <form class="mb-3">
                    @include('common.messages')    
                    <div class="row mt-4">
                        <div class="form-group col-sm-5">
                            <label >Código Sugerido:</label><span class="ml-2">{{$codigo_sugerido}}</span>
                            <input id="codigo" wire:model.lazy="codigo" type="text" class="form-control text-center" placeholder= "código">
                        </div>
                        <div class="form-group col-sm-7">
                            <label >Nombre del Producto</label>
                            <input id="nombre" wire:model.lazy="descripcion" type="text" class="form-control text-capitalize"  placeholder="nombre">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-5 ">
                        <label >Categoría</label>
                        <div class="input-group">
                            <select wire:model="rubro" class="form-control text-center">
                                <option value="Elegir" disabled="">Elegir</option>
                                @foreach($rubros as $t)
                                <option value="{{ $t->id }}">
                                    {{$t->descripcion}}
                                </option>                                       
                                @endforeach                              
                            </select>
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="openModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg></span>
                            </div>			               
                        </div>
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label >Estado</label>
                            <select wire:model="estado" class="form-control text-center">
                                <option value="DISPONIBLE">Disponible</option>
                                <option value="SUSPENDIDO">Suspendido</option>
                                <option value="SIN STOCK">Sin Stock</option>
                            </select>
                        </div>	

                        <div class="form-group col-md-3 col-sm-12">
                            <label >Stock</label>
                            <input wire:model.lazy="stock" type="text" class="form-control"  placeholder="stock">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4 col-sm-12">
                            <label >Tipo</label>
                            <select wire:model="tipo" class="form-control text-center">
                                <option value="Art. Venta">Art. Venta</option>
                                <option value="Art. Compra">Art. Compra</option>
                                <option value="Ambos">Ambos</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4 col-sm-12">
                            <label >Precio de Costo</label>
                            <input wire:model.lazy="precio_costo" onblur="calcularPrecioVenta()" type="text" class="form-control"  placeholder="precio de costo">
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label >Precio de Venta</label>
                            <input wire:model.lazy="precio_venta" type="text" class="form-control"  placeholder="precio de venta">
                        </div>                        
                    </div>
                </form>
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

@section('content_script_head')   
<script>
   function calcularPrecioVenta() {
        window.livewire.emit('calcular_precio_venta');
    }
</script>
@endsection

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
            toastr.success('info', 'Registro eliminado con éxito')
            swal.close()   
        })       
    }
    window.onload = function() {
        document.getElementById("search").focus();
    }
    function setfocus($id) {
        document.getElementById($id).focus();
    }
 
    function openModal()
    {        
        $('#descripcion').val('')
        $('#margen').val('')
        $('#modalRubros').modal('show')
	}
	function saveRubro()
    {
        if($('#descripcion').val() == '')
        {
            toastr.error('El campo Descripción no puede estar vacío')
            return;
        }
        var data = JSON.stringify({
            'descripcion': $('#descripcion').val()
            'margen': $('#margen').val()
        });

        $('#modalRubros').modal('hide')
        window.livewire.emit('createRubroFromModal', data)
    } 
    
</script>