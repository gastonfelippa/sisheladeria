<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //lista de permisos
        Permission::create(['name' => 'Estadisticas_index']);
        Permission::create(['name' => 'Abm_index']);
        
        Permission::create(['name' => 'Config_index']);
        Permission::create(['name' => 'Empresa_index']);
        Permission::create(['name' => 'Permisos_index']);

        Permission::create(['name' => 'Productos_index']);
        Permission::create(['name' => 'Productos_create']);
        Permission::create(['name' => 'Productos_edit']);
        Permission::create(['name' => 'Productos_destroy']);

        Permission::create(['name' => 'Rubros_index']);
        Permission::create(['name' => 'Rubros_create']);
        Permission::create(['name' => 'Rubros_edit']);
        Permission::create(['name' => 'Rubros_destroy']);

        Permission::create(['name' => 'Empleados_index']);
        Permission::create(['name' => 'Empleados_create']);
        Permission::create(['name' => 'Empleados_edit']);
        Permission::create(['name' => 'Empleados_destroy']);

        Permission::create(['name' => 'Clientes_index']);
        Permission::create(['name' => 'Clientes_create']);
        Permission::create(['name' => 'Clientes_edit']);
        Permission::create(['name' => 'Clientes_destroy']);

        Permission::create(['name' => 'Gastos_index']);
        Permission::create(['name' => 'Gastos_create']);
        Permission::create(['name' => 'Gastos_edit']);
        Permission::create(['name' => 'Gastos_destroy']);

        Permission::create(['name' => 'Facturas_index']);
        Permission::create(['name' => 'Facturas_create_producto']);
        Permission::create(['name' => 'Facturas_edit_item']);
        Permission::create(['name' => 'Facturas_destroy_item']);

        Permission::create(['name' => 'Caja_index']);
        Permission::create(['name' => 'HacerCorte_index']);
        Permission::create(['name' => 'MovimientosDiarios_index']);
        Permission::create(['name' => 'CajaRepartidor_index']);

        Permission::create(['name' => 'Reportes_index']);
        Permission::create(['name' => 'VentasDiarias_index']);
        Permission::create(['name' => 'VentasPorFechas_index']);

        Permission::create(['name' => 'Usuarios_index']);
        Permission::create(['name' => 'Usuarios_create']);
        Permission::create(['name' => 'Usuarios_edit']);
        Permission::create(['name' => 'Usuarios_destroy']);

        //lista de roles
        $superadmin = Role::create(['name' => 'SuperAdmin']);      
        $admin = Role::create(['name' => 'Admin']);
        $empleado = Role::create(['name' => 'Empleado']);
        $cliente = Role::create(['name' => 'Cliente']);      

        $superadmin->givePermissionTo([
            'Productos_index',
            'Productos_create',
            'Productos_edit',
            'Productos_destroy',
            'Rubros_index',
            'Rubros_create',
            'Rubros_edit',
            'Rubros_destroy',
            'Empleados_index',
            'Empleados_create',
            'Empleados_edit',
            'Empleados_destroy',
            'Clientes_index',
            'Clientes_create',
            'Clientes_edit',
            'Clientes_destroy',
            'Gastos_index',
            'Gastos_create',
            'Gastos_edit',
            'Gastos_destroy',
            'Usuarios_index',
            'Usuarios_create',
            'Usuarios_edit',
            'Usuarios_destroy',
            'Facturas_index',
            'Facturas_create_producto',
            'Facturas_edit_item',
            'Facturas_destroy_item',
            'Caja_index',
            'HacerCortes_index',
            'MovimientosDiarios_index',
            'CajaRepartidor_index',
            'Estadisticas_index',
            'Abm_index',
            'Config_index',
            'Reportes_index',
            'VentasDiarias_index',
            'VentasPorFechas_index'
        ]);

        $user = User::find(1);
        $user->assingRole('SuperAdmin');
    }
}
