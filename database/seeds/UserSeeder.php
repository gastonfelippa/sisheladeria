<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\TipoComercio;
use App\Comercio;

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
        Permission::create(['name' => 'CorteDeCaja_index']);
        Permission::create(['name' => 'MovimientosDiarios_index']);
        Permission::create(['name' => 'CajaRepartidor_index']);

        Permission::create(['name' => 'Reportes_index']);
        Permission::create(['name' => 'VentasDiarias_index']);
        Permission::create(['name' => 'VentasPorFechas_index']);

        Permission::create(['name' => 'Usuarios_index']);
        Permission::create(['name' => 'Usuarios_create']);
        Permission::create(['name' => 'Usuarios_edit']);
        Permission::create(['name' => 'Usuarios_destroy']);
        
        Permission::create(['name' => 'Movimientos_index']);
        Permission::create(['name' => 'Movimientos_create']);
        Permission::create(['name' => 'Movimientos_edit']);
        Permission::create(['name' => 'Movimientos_destroy']);
        
        //creamos tipos de comercio
        TipoComercio::create(['descripcion' => 'Tipo Abonado']);
        TipoComercio::create(['descripcion' => 'Bar/Pub/Restó']);
        TipoComercio::create(['descripcion' => 'Restaurante']);
        TipoComercio::create(['descripcion' => 'Pizzería']);
        TipoComercio::create(['descripcion' => 'Cervecería']);
        TipoComercio::create(['descripcion' => 'Heladería']);
        TipoComercio::create(['descripcion' => 'Cafetería']);
        TipoComercio::create(['descripcion' => 'Rotisería']);
        TipoComercio::create(['descripcion' => 'Panadería']);
        TipoComercio::create(['descripcion' => 'Otro comercio gastronómico']);
        TipoComercio::create(['descripcion' => 'Otro comercio no gastronómico']);
        

        //creamos un comercio ficticio para poder crear el rol SuperAdmin
        $comercio = Comercio::create(['nombre' => 'ABONADO', 'tipo_id' => '1']);
        // //lista de roles        
        $superadmin = Role::create(['name' => 'SuperAdmin', 'comercio_id' => '1']); 
             
        // $admin = Role::create(['name' => 'Admin']);
        // $empleado = Role::create(['name' => 'Empleado']);
        // $cliente = Role::create(['name' => 'Cliente']);      
        
        $superadmin->givePermissionTo([
            'Estadisticas_index',
            'Abm_index',
            'Config_index',
            'Empresa_index',
            'Permisos_index',
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
            'Facturas_index',
            'Facturas_create_producto',
            'Facturas_edit_item',
            'Facturas_destroy_item',
            'Caja_index',
            'CorteDeCaja_index',
            'MovimientosDiarios_index',
            'CajaRepartidor_index',
            'Reportes_index',
            'VentasDiarias_index',
            'VentasPorFechas_index',
            'Usuarios_index',
            'Usuarios_create',
            'Usuarios_edit',
            'Usuarios_destroy',
            'Movimientos_index',
            'Movimientos_create',
            'Movimientos_edit',
            'Movimientos_destroy',
            'Facturas_imp',
            'Fact_delivery_imp'
        ]);
        

        // User::create([
        //     'name' => 'Admin',
        //     'apellido'=> 'Admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('admin'),
        //     'telefono' => '0',
        //     'movil' => '0',
        //     'direccion' => ''
        // ]);
     

        // $user = User::find(1);
        // $user->assignRole('SuperAdmin');
    }
}
