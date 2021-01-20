<div class="widget-content-area">
    <div class="widget-one">
        @include('common.messages')
        <div class="row">
            <div class="form-group col-md-4 col-sm-12">
                <label>Nombre</label>
                <input type="text" wire:model.lazy="name" class="form-control" placeholder="nombre">
            </div>
            <div class="form-group col-md-4 col-sm-12">
                <label>Apellido</label>
                <input type="text" wire:model.lazy="apellido" class="form-control" placeholder="apellido">
            </div>
            <div class="form-group col-md-4 col-sm-12">
                <label>Teléfono</label>
                <input type="text" wire:model.lazy="telefono" class="form-control" placeholder="teléfono" maxlength="10">
            </div>
            <div class="form-group col-md-4 col-sm-12">
                <label>Dirección</label>
                <input type="text" wire:model.lazy="direccion" class="form-control" placeholder="dirección">
            </div>
            <!-- <div class="form-group col-md-4 col-sm-12">
                <label>Tipo</label>
                <select wire:model="tipo" class="form-control text-center">
                    <option value="Elegir">Elegir</option>
                    <option value="Admin">Administrador</option>
                    <option value="Empleado">Empleado</option>
                    <option value="Cliente">Cliente</option>
                </select>
            </div> -->
            <div class="form-group col-md-4 col-sm-12">
                <label>Email</label>
                <input type="text" wire:model.lazy="email" class="form-control" placeholder="correo@gmail.com">
            </div>
            <div class="form-group col-md-4 col-sm-12">
                <label>Password</label>
                <input type="password" wire:model.lazy="password" class="form-control" placeholder="contraseña">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 mt-2 text-left">
                <button type="button" wire:click.prevent="doAction(1)" class="btn btn-dark mr-1">
                    <i class="mbri-left">Regresar</i>   
                </button>
                <button type="button" wire:click.prevent="StoreOrUpdate()" class="btn btn-primary mr-1 ml-2">
                    <i class="mbri-succes">Guardar</i>   
                </button>
            </div>
        </div>
    </div>
</div>