<div class="row layout-top-spacing">    
    <div class="col-sm-12 col-md-7 layout-spacing">             
        <div class="widget-content-area">
            <div class="widget-one">
                <div class="row">
                    <div class="col-xl-12 text-center">
                        <h3><b>Productos</b></h3>
                    </div> 
                </div>    		
				@include('common.inputBuscarBtnNuevo')   
                @include('common.alerts') <!-- mensajes -->  
                <div class="table-responsive scroll">
                    <table class="table table-hover table-checkable table-sm">
                        <thead>
                            <tr>                                                   
                                <th class="">ID</th>
                                <th class="">DESCRIPCIÓN</th>
                                <th class="">PR. COSTO</th>
                                <th class="">PR. VENTA</th>
                                <th class="">ESTADO</th>
                                <th class="">RUBRO</th>
                                <th class="text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($info as $r)
                            <tr>                     
                                <td><p class="mb-0">{{$r->id}}</p></td>
                                <td>{{$r->descripcion}}</td>
                                <td>{{$r->precio_costo}}</td>
                                <td>{{$r->precio_venta}}</td>
                                <td>{{$r->estado}}</td>
                                <td>{{$r->rubro}}</td>
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

    <div class="col-sm-12 col-md-5 layout-spacing"> 
        <div class="widget-content-area ">
            <div class="widget-one">
                <h5>
                    <b>@if($selected_id ==0) Crear Nuevo Producto  @else Editar Producto @endif  </b>
                </h5>
                <form>
                    @include('common.messages')    
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label >Nombre del Producto</label>
                            <input id="nombre" wire:model.lazy="descripcion" type="text" class="form-control text-capitalize"  placeholder="nombre">
                        </div>

                        <div class="form-group col-md-6 col-sm-12">
                            <label >Precio de Costo</label>
                            <input wire:model.lazy="precio_costo" type="text" class="form-control"  placeholder="precio de costo">
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label >Precio de Venta</label>
                            <input wire:model.lazy="precio_venta" type="text" class="form-control"  placeholder="precio de venta">
                        </div>

                        <div class="form-group col-md-6 col-sm-12">
                            <label >Rubro</label>
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
                                <option value="SUSPENDIDO">SIN STOCK</option>
                            </select>
                        </div>	
                    </div>
                </form>
                @include('common.btnCancelarGuardar')
            </div>
        </div>
    </div>
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
</script>