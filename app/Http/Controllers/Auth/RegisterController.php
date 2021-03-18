<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Spatie\Permission\Models\Role;
use App\User;
use App\Comercio;
use App\UsuarioComercio;
use App\UsuarioComercioPlanes;
use App\Plan;
use App\ModelHasRole;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    
     


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {     
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'nombreComercio' => ['required', 'string', 'max:255','unique:comercios,nombre'],
            'sexo' => ['required', 'not_in:0'],
            'email' => ['required', 'string', 'email', 'max:255'],
            ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        //genera un password random de 8 caracteres y crea una sesion con ese password
        $password = Str::random(8);
        session(['pass' => $password]);
        session(['empleado' => false]);

        $cadena = strtolower($data['nombreComercio']);
        $username = str_replace(' ', '',Str::finish('admin@', $cadena));     
        
        DB::begintransaction();                 //iniciar transacción para grabar
        try{    
            $comercio = Comercio::create([
                'nombre' => strtoupper($data['nombreComercio']),            
                'tipo_id' => $data['tipo']            
            ]);
                
            $user = User::create([            
                'name' => ucwords($data['name']),
                'apellido' => ucwords($data['apellido']),
                'sexo' => $data['sexo'],
                'username' => $username,
                'email' => strtolower($data['email']),
                'password' => Hash::make($password),
                'pass' => $password,
                'abonado' => 'Si'
            ]);
                
            UsuarioComercio::create([
                'usuario_id' => $user->id,            
                'comercio_id' => $comercio->id            
            ]);
            //creo los roles Admin, No Usuario y Repartidor            
            $rolAdmin = Role::create([
                'name' => 'Admin'. $comercio->id,
                'alias' => 'Admin',
                'comercio_id' => $comercio->id         
            ]);            
            
            Role::create([
                'name' => 'No Usuario'. $comercio->id,
                'alias' => 'No Usuario',
                'comercio_id' => $comercio->id         
                ]);
                
            Role::create([
                'name' => 'Repartidor'. $comercio->id,
                'alias' => 'Repartidor',
                'comercio_id' => $comercio->id         
            ]);
            //Asigno el rol Admin al nuevo Usuario
            ModelHasRole::create([
                'role_id' => $rolAdmin->id,
                'model_type' => 'App\User',           
                'model_id' => $user->id           
            ]);
                                
            $rolAdmin->givePermissionTo([
                'Estadisticas_index','Abm_index','Config_index','Empresa_index','Permisos_index','Productos_index',
                'Productos_create','Productos_edit','Productos_destroy','Rubros_index','Rubros_create','Rubros_edit',
                'Rubros_destroy','Empleados_index','Empleados_create','Empleados_edit','Empleados_destroy',
                'Clientes_index','Clientes_create','Clientes_edit','Clientes_destroy', 'Proveedores_index',
                'Proveedores_create','Proveedores_edit','Proveedores_destroy','Gastos_index','Gastos_create',
                'Gastos_edit','Gastos_destroy','Facturas_index','Facturas_create_producto','Facturas_edit_item',
                'Facturas_destroy_item', 'Compras_index','Compras_create_producto','Compras_edit_item',
                'Compras_destroy_item','Caja_index','CorteDeCaja_index','MovimientosDiarios_index','CajaRepartidor_index',
                'Reportes_index','VentasDiarias_index','VentasPorFechas_index','Usuarios_index','Usuarios_create','Usuarios_edit',
                'Usuarios_destroy','Movimientos_create','Movimientos_edit','Movimientos_destroy','Facturas_imp','Fact_delivery_imp'           
            ]);                                    
                                    
            $usercomercio = UsuarioComercio::select('id')->orderBy('id', 'desc')->get();
            $plan = Plan::select('*')->where('id', '1')->get(); 
                                    
            $fecha_inicio = Carbon::now()->locale('en');      //inicializo fecha_inicio con la fecha en que se suscribe al sistema
            $mes = $fecha_inicio->monthName;                  //recupero el mes
            Carbon::setTestNow($fecha_inicio);                //habilito a Carbon para que actúe sobre fecha_inicio
            $fecha_fin = new Carbon('last day of ' . $mes);   //inicializo fecha_fin con el último día del mes en curso
            $diferencia = $fecha_inicio->diffInDays($fecha_fin); //efectúo la diferencia entre fechas para saber los días que las separan
                                    
            if($diferencia < 15)                                 //si son menos de 15 días
            {                                  
                $fecha_fin = Carbon::now()->addMonthsNoOverflow(1)->locale('en'); //agrego un mes a fecha_fin a partir del corriente mes
                $mes = $fecha_fin->monthName;                         //recupero el mes
                Carbon::setTestNow($fecha_fin);                       //habilito a Carbon para que actúe sobre fecha_fin
                $fecha_fin = new Carbon('last day of ' . $mes);       //modifico fecha_fin con el último día del mes siguiente
            }
            Carbon::setTestNow();               //IMPORTANTE: resetea la fecha actual para grabarla en create_at y update_at
            
            UsuarioComercioPlanes::create([
                'usuariocomercio_id'   => $usercomercio[0]->id,
                'plan_id'              => $plan[0]->id,
                'estado_plan'          => 'activo',
                'importe'              => $plan[0]->precio,
                'estado_pago'          => 'no corresponde',
                'fecha_inicio_periodo' => Carbon::parse($fecha_inicio)->format('Y,m,d') . ' 00:00:00',
                'fecha_fin'            => Carbon::parse($fecha_fin)->format('Y,m,d') . ' 23:59:59',
                'fecha_vto'            => Carbon::parse($fecha_fin)->format('Y,m,d') . ' 23:59:59',
                'comentarios'          => 'Inicio plan de prueba'
                ]);
                
            DB::commit();
            return $user;
        }catch (Exception $e){
            DB::rollback();    //en caso de error, deshacemos para no generar inconsistencia de datos  
            session()->flash('msg-error', '¡¡¡ATENCIÓN!!! El registro no se grabó...');
        }
    }
}
    