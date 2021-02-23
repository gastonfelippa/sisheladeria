<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\TipoComercio;
use App\Comercio;
use App\Plan;
use App\Proceso;
use App\Provincia;

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
        Permission::create(['name' => 'Estadisticas_index', 'alias' => 'Ver']);
        Permission::create(['name' => 'Abm_index', 'alias' => 'Ver']);
        
        Permission::create(['name' => 'Config_index', 'alias' => 'Ver']);
        Permission::create(['name' => 'Empresa_index', 'alias' => 'Ver Empresa']);
        Permission::create(['name' => 'Permisos_index', 'alias' => 'Ver Permisos']);

        Permission::create(['name' => 'Productos_index', 'alias' => 'Ver']);
        Permission::create(['name' => 'Productos_create', 'alias' => 'Agregar']);
        Permission::create(['name' => 'Productos_edit', 'alias' => 'Modificar']);
        Permission::create(['name' => 'Productos_destroy', 'alias' => 'Eliminar']);

        Permission::create(['name' => 'Rubros_index', 'alias' => 'Ver']);
        Permission::create(['name' => 'Rubros_create', 'alias' => 'Agregar']);
        Permission::create(['name' => 'Rubros_edit', 'alias' => 'Modificar']);
        Permission::create(['name' => 'Rubros_destroy', 'alias' => 'Eliminar']);

        Permission::create(['name' => 'Empleados_index', 'alias' => 'Ver']);
        Permission::create(['name' => 'Empleados_create', 'alias' => 'Agregar']);
        Permission::create(['name' => 'Empleados_edit', 'alias' => 'Modificar']);
        Permission::create(['name' => 'Empleados_destroy', 'alias' => 'Eliminar']);

        Permission::create(['name' => 'Clientes_index', 'alias' => 'Ver']);
        Permission::create(['name' => 'Clientes_create', 'alias' => 'Agregar']);
        Permission::create(['name' => 'Clientes_edit', 'alias' => 'Modificar']);
        Permission::create(['name' => 'Clientes_destroy', 'alias' => 'Eliminar']);

        Permission::create(['name' => 'Gastos_index', 'alias' => 'Ver']);
        Permission::create(['name' => 'Gastos_create', 'alias' => 'Agregar']);
        Permission::create(['name' => 'Gastos_edit', 'alias' => 'Modificar']);
        Permission::create(['name' => 'Gastos_destroy', 'alias' => 'Eliminar']);

        Permission::create(['name' => 'Facturas_index', 'alias' => 'Crear']);
        Permission::create(['name' => 'Facturas_edit_item', 'alias' => 'Modificar']);
        Permission::create(['name' => 'Facturas_destroy_item', 'alias' => 'Eliminar']);
        Permission::create(['name' => 'Facturas_create_producto', 'alias' => 'Agregar Producto']);

        Permission::create(['name' => 'Caja_index', 'alias' => 'Ver Caja']);
        Permission::create(['name' => 'CorteDeCaja_index', 'alias' => 'Ver Corte De Caja']);
        Permission::create(['name' => 'MovimientosDiarios_index', 'alias' => 'Ver Movimientos Diarios']);
        Permission::create(['name' => 'CajaRepartidor_index', 'alias' => 'Ver Caja Repartidor']);

        Permission::create(['name' => 'Reportes_index', 'alias' => 'Ver Reportes']);
        Permission::create(['name' => 'VentasDiarias_index', 'alias' => 'Ver Ventas Diarias']);
        Permission::create(['name' => 'VentasPorFechas_index', 'alias' => 'Ver Ventas Por Fecha']);

        Permission::create(['name' => 'Usuarios_index', 'alias' => 'Ver']);
        Permission::create(['name' => 'Usuarios_create', 'alias' => 'Agregar']);
        Permission::create(['name' => 'Usuarios_edit', 'alias' => 'Modificar']);
        Permission::create(['name' => 'Usuarios_destroy', 'alias' => 'Eliminar']);
        
        Permission::create(['name' => 'Movimientos_index', 'alias' => 'Ver']);
        Permission::create(['name' => 'Movimientos_create', 'alias' => 'Agregar']);
        Permission::create(['name' => 'Movimientos_edit', 'alias' => 'Modificar']);
        Permission::create(['name' => 'Movimientos_destroy', 'alias' => 'Eliminar']);

        Permission::create(['name' => 'Facturas_imp', 'alias' => 'Ver']);
        Permission::create(['name' => 'Fact_delivery_imp', 'alias' => 'Ver']);

        Permission::create(['name' => 'Planes_index', 'alias' => 'Ver']);
        Permission::create(['name' => 'Abonados_index', 'alias' => 'Ver']);
        Permission::create(['name' => 'Procedimientos_index', 'alias' => 'Ver']);
        
        //creamos tipos de comercio
        TipoComercio::create(['descripcion' => 'Tipo SuperAdmin']);
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

        Plan::create([
            'descripcion' => 'PRUEBA', 
            'precio'      => '0',
            'duracion'    => '30',
            'estado'      => 'activo'
        ]);
        
 
        //creamos un comercio ficticio para poder crear el rol SuperAdmin
        $comercio = Comercio::create(['nombre' => 'SUPERADMIN', 'tipo_id' => '1']);
        $comercio = Comercio::create(['nombre' => 'ABONADO', 'tipo_id' => '2']);

        // //lista de roles        
        $superadmin = Role::create(['name' => 'SuperAdmin', 'alias' => 'SuperAdmin','comercio_id' => '1']); 
        
        //asignación de permisos a roles
        $superadmin->givePermissionTo([   
            'Planes_index',  
            'Abonados_index',
            'Procedimientos_index'
        ]);  
        
        Provincia::create(['descripcion' => 'Buenos Aires']);
        Provincia::create(['descripcion' => 'Catamarca']);
        Provincia::create(['descripcion' => 'Chaco']);
        Provincia::create(['descripcion' => 'Chubut']);
        Provincia::create(['descripcion' => 'Córdoba']);
        Provincia::create(['descripcion' => 'Corrientes']);
        Provincia::create(['descripcion' => 'Entre Ríos']);
        Provincia::create(['descripcion' => 'Formosa']);
        Provincia::create(['descripcion' => 'Jujuy']);
        Provincia::create(['descripcion' => 'La Pampa']);
        Provincia::create(['descripcion' => 'La Rioja']);
        Provincia::create(['descripcion' => 'Mendoza']);
        Provincia::create(['descripcion' => 'Misiones']);
        Provincia::create(['descripcion' => 'Neuquén']);
        Provincia::create(['descripcion' => 'Río Negro']);
        Provincia::create(['descripcion' => 'Salta']);
        Provincia::create(['descripcion' => 'San Juan']);
        Provincia::create(['descripcion' => 'San Luis']);
        Provincia::create(['descripcion' => 'Santa Cruz']);
        Provincia::create(['descripcion' => 'Santa Fe']);
        Provincia::create(['descripcion' => 'Santiago del Estero']);
        Provincia::create(['descripcion' => 'Tierra del Fuego']);
        Provincia::create(['descripcion' => 'Tucumán']); 

        User::create([
            'name' => 'Gastón',
            'apellido'=> 'Felippa', 
            'sexo' => '2',
            'username' => 'admin@floki',
            'email' => 'admin@gmail',
            'password' => bcrypt('12345678'),
            'pass' => '12345678',
            'abonado' => 'Admin'
        ]);     

        $user = User::find(1);
        $user->assignRole('SuperAdmin');

        Proceso::create(['descripcion' => 'Renovación Automática De Planes', 'dia_ejecucion'=> '1']);
        Proceso::create(['descripcion' => 'Plan De Prueba Finalizado', 'dia_ejecucion'=> '1']);
        Proceso::create(['descripcion' => 'Plan Activo En Mora', 'dia_ejecucion'=> '11']);
        Proceso::create(['descripcion' => 'Plan Activo Impago', 'dia_ejecucion'=> '16']);
    }
}
