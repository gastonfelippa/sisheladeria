<div class="row layout-top-spacing">    
    <div class="col-sm-12 col-md-7 layout-spacing">             
        <div class="widget-content-area">
            <div class="widget-one">
                <div class="row">
                    <div class="col-xl-12 text-center">
                        <h3><b>Productos</b></h3>
                    </div> 
                </div>    		
				@include('common.inputBuscarBtnNuevo', ['create' => 'Productos_create'])  
                <!-- <div class="row justify-content-between mb-3">
                    <div class="col-sm-">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></span>
                            </div>
                            <input id="search" type="text" wire:model="search" class="form-control" placeholder="Buscar.." aria-label="notification" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-sm-3 mr-2">
                        @can('Productos_create')
                        <button type="button" wire:click="doAction(2)" onclick="setfocus('codigo')" class="btn btn-dark">
                            <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users mr-2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            Nuevo
                        </button>
                        @endcan
                    </div>
                </div>  -->
                @include('common.alerts') <!-- mensajes -->  
                <div class="table-responsive scroll">
                    <table class="table table-hover table-checkable table-sm">
                        <thead>
                            <tr>                                                   
                                <th class="">CODIGO</th>
                                <th class="">DESCRIPCIÓN</th>
                                <!-- @can('Productos_create') -->
                                <th class="text-center">PR. COSTO</th>
                                <!-- @endcan -->
                                <th class="text-center">PR. VENTA</th>
                                <th class="text-center">ESTADO</th>
                                <!-- @can('Productos_create') -->
                                <th class="">RUBRO</th>
                                <th class="text-center">ACCIONES</th>
                                <!-- @endcan -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($info as $r)
                            <tr>                     
                                <td class="text-center"><p class="mb-0">{{$r->codigo}}</p></td>
                                <td>{{$r->descripcion}}</td>
                                <!-- @can('Productos_create') -->
                                <td class="text-right">{{$r->precio_costo}}</td>
                                <!-- @endcan -->
                                <td class="text-right">{{$r->precio_venta}}</td>
                                <td class="text-center">{{$r->estado}}</td>
                                <!-- @can('Productos_create') -->
                                <td>{{$r->rubro}}</td>
                                <!-- @endcan -->
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
    <div class="col-sm-12 col-md-5 layout-spacing"> 
        <div class="widget-content-area ">
            <div class="widget-one">
                <h5>
                    <b>@if($selected_id ==0) Crear Nuevo Producto  @else Editar Producto @endif  </b>
                </h5>
                <form>
                    @include('common.messages')    
                    <div class="row mt-4 mb-3">
                        <div class="form-group col-sm-5">
                            <label >Código Sugerido:</label><span class="ml-2">{{$codigo_sugerido}}</span>
                            <input id="codigo" wire:model.lazy="codigo" type="text" class="form-control text-center" placeholder= "código">
                        </div>
                        <div class="form-group col-sm-7">
                            <label >Nombre del Producto</label>
                            <input id="nombre" wire:model.lazy="descripcion" type="text" class="form-control text-capitalize"  placeholder="nombre">
                        </div>

                        <div class="form-group col-md-6 col-sm-12">
                            <label >Rubro</label><span class="badge badge-primary ml-5" >
                                    <a href="{{ url('rubros') }}" style="color: white">Agregar</a></span> 
                            <select wire:model="rubro" class="form-control text-center">
                                <option value="Elegir" disabled="">Elegir</option>
                                @foreach($rubros as $t)
                                <option value="{{ $t->id }}">
                                    {{$t->descripcion}}
                                </option>                                       
                                @endforeach                              
                            </select>			               
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label >Estado</label>
                            <select wire:model="estado" class="form-control text-center">
                                <option value="DISPONIBLE">DISPONIBLE</option>
                                <option value="SUSPENDIDO">SUSPENDIDO</option>
                                <option value="SIN STOCK">SIN STOCK</option>
                            </select>
                        </div>	

                        <div class="form-group col-md-6 col-sm-12">
                            <label >Precio de Costo</label>
                            <input wire:model.lazy="precio_costo" onblur="calcularPrecioVenta()" type="text" class="form-control"  placeholder="precio de costo">
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
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
    function calcularPrecioVenta() {
        window.livewire.emit('calcular_precio');
    }
    
</script>