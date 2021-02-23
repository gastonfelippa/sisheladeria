<div class="tab-pane fade {{$tab == 'permisos' ? 'show active' : ''}}" id="permisos_content" role="tabpanel">
    <div class="row mt-2">
        <div class="col-sm-12 col-md-8">
            <h6 class="text-center"><b>PERMISOS DE SISTEMA</b></h6>
            <div class="table-responsive scroll">
                <div id="tblPermisos" class="row row-cols-1 row-cols-md-3 g-4">
                    <div class="col">
                        <div class="card border-dark text-dark bg-light mb-3">
                            <div class="card-header">Productos</div>
                            <div class="card-body">
                            @foreach($pProductos as $p)
                            <tr>
                                <td class="text-center">
                                    <div class="n-check" id="divPermisos">
                                        <label class="new-control new-checkbox checkbox-primary">
                                            <input data-name="{{$p->name}}" 
                                            {{$p->checked == 1 ? 'checked' : ''}}
                                            type="checkbox" class="new-control-input checkbox-rol">
                                            <span class="new-control-indicator"></span>
                                            {{$p->alias}}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border-dark text-dark bg-light mb-3">
                            <div class="card-header">Clientes</div>
                            <div class="card-body">
                            @foreach($pClientes as $p)
                            <tr>
                                <td class="text-center">
                                    <div class="n-check" id="divPermisos">
                                        <label class="new-control new-checkbox checkbox-primary">
                                            <input data-name="{{$p->name}}" 
                                            {{$p->checked == 1 ? 'checked' : ''}}
                                            type="checkbox" class="new-control-input checkbox-rol">
                                            <span class="new-control-indicator"></span>
                                            {{$p->alias}}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border-dark text-dark bg-light mb-3">
                            <div class="card-header">Empleados</div>
                            <div class="card-body">
                            @foreach($pEmpleados as $p)
                            <tr>
                                <td class="text-center">
                                    <div class="n-check" id="divPermisos">
                                        <label class="new-control new-checkbox checkbox-primary">
                                            <input data-name="{{$p->name}}" 
                                            {{$p->checked == 1 ? 'checked' : ''}}
                                            type="checkbox" class="new-control-input checkbox-rol">
                                            <span class="new-control-indicator"></span>
                                            {{$p->alias}}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border-dark text-dark bg-light mb-3" >
                            <div class="card-header">Facturas</div>
                            <div class="card-body">
                            @foreach($pFacturas as $p)
                            <tr>
                                <td class="text-center">
                                    <div class="n-check" id="divPermisos">
                                        <label class="new-control new-checkbox checkbox-primary">
                                            <input data-name="{{$p->name}}" 
                                            {{$p->checked == 1 ? 'checked' : ''}}
                                            type="checkbox" class="new-control-input checkbox-rol">
                                            <span class="new-control-indicator"></span>
                                            {{$p->alias}}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border-dark text-dark bg-light mb-3">
                            <div class="card-header">Rubros</div>
                            <div class="card-body">
                            @foreach($pRubros as $p)
                            <tr>
                                <td class="text-center">
                                    <div class="n-check" id="divPermisos">
                                        <label class="new-control new-checkbox checkbox-primary">
                                            <input data-name="{{$p->name}}" 
                                            {{$p->checked == 1 ? 'checked' : ''}}
                                            type="checkbox" class="new-control-input checkbox-rol">
                                            <span class="new-control-indicator"></span>
                                            {{$p->alias}}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border-dark text-dark bg-light mb-3">
                            <div class="card-header">Gastos</div>
                            <div class="card-body">
                            @foreach($pGastos as $p)
                            <tr>
                                <td class="text-center">
                                    <div class="n-check" id="divPermisos">
                                        <label class="new-control new-checkbox checkbox-primary">
                                            <input data-name="{{$p->name}}" 
                                            {{$p->checked == 1 ? 'checked' : ''}}
                                            type="checkbox" class="new-control-input checkbox-rol">
                                            <span class="new-control-indicator"></span>
                                            {{$p->alias}}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border-dark text-dark bg-light mb-3">
                            <div class="card-header">Usuarios</div>
                            <div class="card-body">
                            @foreach($pUsuarios as $p)
                            <tr>
                                <td class="text-center">
                                    <div class="n-check" id="divPermisos">
                                        <label class="new-control new-checkbox checkbox-primary">
                                            <input data-name="{{$p->name}}" 
                                            {{$p->checked == 1 ? 'checked' : ''}}
                                            type="checkbox" class="new-control-input checkbox-rol">
                                            <span class="new-control-indicator"></span>
                                            {{$p->alias}}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border-dark text-dark bg-light mb-3">
                            <div class="card-header">Reportes</div>
                            <div class="card-body">
                            @foreach($pReportes as $p)
                            <tr>
                                <td class="text-center">
                                    <div class="n-check" id="divPermisos">
                                        <label class="new-control new-checkbox checkbox-primary">
                                            <input data-name="{{$p->name}}" 
                                            {{$p->checked == 1 ? 'checked' : ''}}
                                            type="checkbox" class="new-control-input checkbox-rol">
                                            <span class="new-control-indicator"></span>
                                            {{$p->alias}}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border-dark text-dark bg-light mb-3">
                            <div class="card-header">Configuraciones</div>
                            <div class="card-body">
                            @foreach($pConfiguraciones as $p)
                            <tr>
                                <td class="text-center">
                                    <div class="n-check" id="divPermisos">
                                        <label class="new-control new-checkbox checkbox-primary">
                                            <input data-name="{{$p->name}}" 
                                            {{$p->checked == 1 ? 'checked' : ''}}
                                            type="checkbox" class="new-control-input checkbox-rol">
                                            <span class="new-control-indicator"></span>
                                            {{$p->alias}}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border-dark text-dark bg-light mb-3">
                            <div class="card-header">Caja</div>
                            <div class="card-body">
                            @foreach($pCaja as $p)
                            <tr>
                                <td class="text-center">
                                    <div class="n-check" id="divPermisos">
                                        <label class="new-control new-checkbox checkbox-primary">
                                            <input data-name="{{$p->name}}" 
                                            {{$p->checked == 1 ? 'checked' : ''}}
                                            type="checkbox" class="new-control-input checkbox-rol">
                                            <span class="new-control-indicator"></span>
                                            {{$p->alias}}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border-dark text-dark bg-light mb-3">
                            <div class="card-header">Movimientos de Caja</div>
                            <div class="card-body">
                            @foreach($pMovDeCaja as $p)
                            <tr>
                                <td class="text-center">
                                    <div class="n-check" id="divPermisos">
                                        <label class="new-control new-checkbox checkbox-primary">
                                            <input data-name="{{$p->name}}" 
                                            {{$p->checked == 1 ? 'checked' : ''}}
                                            type="checkbox" class="new-control-input checkbox-rol">
                                            <span class="new-control-indicator"></span>
                                            {{$p->alias}}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
 
                <!-- <table id="tblPermisos" class="table table-hover table-checkable table-sm">
                    <thead>
                        <tr>
                            <th class="text-center">DESCRIPCIÓN</th>
                            <th class="text-center">ROLES <br>con el permiso</th>
                            <th class="text-center">ACCIONES</th>
                            <th class="text-center">
                                <div class="n-check">
                                    <label class="new-control new-checkbox checkbox-primary">
                                        <input type="checkbox" class="new-control-input check-all">
                                        <span class="new-control-indicator"></span>
                                        TODOS
                                    </label>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pProductos as $p)
                        <tr>
                            <td>{{$p->name}}</td>
                            <td class="text-center">{{\App\User::permission($p->name)->count()}}</td>
                            <td class="text-center">
                                <span style="cursor:pointer"
                                onclick="showPermiso('{{$p}}')">
                                    <i class="la la-edit la-2x text-center"></i>
                                </span>
                                @if(\App\User::permission($p->name)->count() <= 0)
                                <a href="javascript:void(0)"
                                onclick="Confirm('{{$p->id}}', 'destroyPermiso')"
                                title="Eliminar permiso">
                                    <i class="la la-trash la-2x text-center"></i>
                                </a>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="n-check" id="divPermisos">
                                    <label class="new-control new-checkbox checkbox-primary">
                                        <input data-name="{{$p->name}}" 
                                        {{$p->checked == 1 ? 'checked' : ''}}
                                        type="checkbox" class="new-control-input checkbox-rol">
                                        <span class="new-control-indicator"></span>
                                        Asignar
                                    </label>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody> 
                </table>-->
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <h6 class="text-left"><b>Elegir Role</b></h6>   
            <div class="input-group">
                <select wire:model="roleSelected" id="roleSelected" class="form-control">
                    <option value="Seleccionar">Seleccionar</option>
                    @foreach($roles as $r)
                    <option value="{{$r->id}}">{{$r->alias}}</option>
                    @endforeach
                </select>
            </div>   
            <button type="button" onclick="AsignarPermisos()" class="btn btn-primary mt-4">Asignar Permisos</button>      
        </div>
    </div>
</div>

<style type="text/css" scoped>
.scroll{
    position: relative;
    height: 300px;
    margin-top: .5rem;
    overflow: auto;
}
</style>
