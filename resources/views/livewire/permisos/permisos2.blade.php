<div class="tab-pane fade {{$tab == 'permisos' ? 'show active' : ''}}" id="permisos_content" role="tabpanel">
    <div class="row mt-2">
        <div class="col-sm-12 col-md-5">
            <h6 class="text-center"><b>PERMISOS DE SISTEMA</b></h6>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"
                    onclick="clearPermissionSelected()">
                        <i class="la la-remove la-lg"></i>
                    </span>
                </div>
                <input type="text" id="permisoName" class="form-control" autocomplete="off">
                <input type="hidden" id="permisoId">
                <div class="input-group-prepend">
                    <span class="input-group-text" 
                    wire:click="$emit('CrearPermiso',$('#permisoName').val(), $('#permisoId').val())">
                        <i class="la la-save la-lg"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-3">
            <h6 class="text-left"><b>Elegir Role</b></h6>   
            <div class="input-group">
                <select wire:model="roleSelected" id="roleSelected" class="form-control">
                    <option value="Seleccionar">Seleccionar</option>
                    @foreach($roles as $r)
                    <option value="{{$r->id}}">{{$r->name}}</option>
                    @endforeach
                </select>
            </div>   
        </div>
            <button type="button" onclick="AsignarPermisos()" class="btn btn-primary mt-4">Asignar Permisos</button>      
    </div>
            <div class="row">
                @include('livewire.modulos.m_productos')
                @include('livewire.modulos.m_clientes')
                @include('livewire.modulos.m_usuarios')
            </div>
            <div class="row">
                @include('livewire.modulos.m_productos')
                @include('livewire.modulos.m_clientes')
                @include('livewire.modulos.m_usuarios')
            </div>
            <div class="row">
                @include('livewire.modulos.m_productos')
                @include('livewire.modulos.m_clientes')
                @include('livewire.modulos.m_usuarios')
            </div>          

</div>


