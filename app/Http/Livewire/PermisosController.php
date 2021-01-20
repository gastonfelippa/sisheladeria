<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\User;
use DB;

class PermisosController extends Component
{
    public $permisoTitle = "Crear", $roleTitle = "Crear", $userSelected;
    public $tab = 'roles', $roleSelected;
    public $comercioId;

    public function render()
    {
         //busca el comercio que está en sesión
        $userComercio = UsuarioComercio::select('comercio_id')
            ->where('usuario_id', Auth()->user()->id)->get();
        $this->comercioId = $userComercio[0]->comercio_id;

        $roles = Role::select('*', DB::RAW("0 as checked"))
        ->where('comercio_id', $userComercio[0]->comercio_id)->get();

        $permisos = Permission::select('*', DB::RAW("0 as checked"))->get();

        if($this->userSelected != '' && $this->userSelected != 'Seleccionar')
        {
            foreach($roles as $r){
                $user = User::find($this->userSelected);
                $tieneRole = $user->hasRole($r->name);
                if($tieneRole){
                        $r->checked = 1;
                }
            }
        }

        if($this->roleSelected != '' && $this->roleSelected != 'Seleccionar')
        {
            foreach($permisos as $p){
                $role = Role::find($this->roleSelected);
                $tienePermiso = $role->hasPermissionTo($p->name);
                if($tienePermiso){
                        $p->checked = 1;
                }
            }
        }

        return view('livewire.permisos.component',[
            'roles' => $roles,
            'permisos' => $permisos,
            'usuarios' => User::select('id', 'nombre')->get()
            ]);
        
    }    
        //sección de roles
    public function resetInput()
    {
        $this->roleTitle = 'Crear';
        $this->permisoTitle = 'Crear';
        $this->userSelected = '';
        $this->roleSelected = '';
    }
        
    protected $listeners = [
        'destroyRole' => 'destroyRole',
        'destroyPermiso' => 'destroyPermiso',
        'CrearRole' => 'CrearRole',
        'CrearPermiso' => 'CrearPermiso',
        'AsignarRoles' => 'AsignarRoles',
        'AsignarPermisos' => 'AsignarPermisos'
    ];        

    public function CrearRole($roleName, $roleId)
    {
        if($roleId)
            $this->UpdateRole($roleName, $roleId);
        else
            $this->SaveRole($roleName);
    }

    public function SaveRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if($role){
            session()->flash('msg-ops', 'El role que intentas registrar ya existe en sistema');
            return;
        }
        Role::create([
            'name' => $roleName,
            'comercio_id' => $this->comercioId
        ]);
        session()->flash('msg-ok', 'El role se registró correctamente');
        $this->resetInput();
    }

    public function UpdateRole($roleName, $roleId)
    {
        $role = Role::where('name', $roleName)->where('id', '<>', $roleId)->first();
        if($role){
            session()->flash('msg-ops', 'El role que intentas registrar ya existe en sistema');
            return;
        }

        $role = Role::find($roleId);
        $role->name = $roleName;
        $role->save();

        session()->flash('msg-ok', 'Role actualizado correctamente');
        $this->resetInput();
    }

    public function destroyRole($roleId)
    {
        Role::find($roleId)->delete();

        session()->flash('msg-ok', 'Se eliminó el role correctamente');
    }

    public function AsignarRoles($rolesList)
    {
        if($this->userSelected){
            $user = User::find($this->userSelected);
            if($user)
            {
                $user->syncRoles($rolesList);
                session()->flash('msg-ok', 'Roles asignados correctamente');
                $this->resetInput();
            }
        }
    }

    //permisos
    public function CrearPermiso($permisoName, $permisoId)
    {
       // dd($permisoName, $permisoId);
        if($permisoId)
            $this->UpdatePermiso($permisoName, $permisoId);
        else
            $this->SavePermiso($permisoName);
    }

    public function SavePermiso($permisoName)
    {
        $permiso = Permission::where('name', $permisoName)->first();
        if($permiso){
            session()->flash('msg-ops', 'El permiso que intentas registrar ya existe en sistema');
            return;
        }

        Permission::create([
            'name' => $permisoName
        ]);
        session()->flash('msg-ok', 'El permiso se registró correctamente');
        $this->resetInput();
    }

    public function UpdatePermiso($permisoName, $permisoId)
    {
        $permiso = Permission::where('name', $permisoName)->where('id', '<>', $permisoId)->first();
        if($permiso){
            session()->flash('msg-ops', 'El permiso que intentas registrar ya existe en sistema');
            return;
        }

        $permiso = Permission::find($permisoId);
        $permiso->name = $permisoName;
        $permiso->save();

        session()->flash('msg-ok', 'Permiso actualizado correctamente');
        $this->resetInput();
    }

    public function destroyPermiso($permisoId)
    {
        Permission::find($permisoId)->delete();

        session()->flash('msg-ok', 'Se eliminó el permiso correctamente');
    }

    public function AsignarPermisos($permisosList, $roleId)
    {
        if($roleId > 0){
            $role = Role::find($roleId);
            if($role)
            {
                $role->syncPermissions($permisosList);
                session()->flash('msg-ok', 'Permisos asignados correctamente');
                $this->resetInput();
            }
        }
    }

}


