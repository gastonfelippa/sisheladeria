<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Comercio;
use App\UsuarioComercio;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = RouteServiceProvider::HOME;


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
            'nombreComercio' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'numeric', 'min:100000000' , 'max:999999999999999'], 
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
        //dd($data);
        $comercio = Comercio::create([
            'nombre' => strtoupper($data['nombreComercio']),            
            'tipo_id' => $data['tipo']            
        ]);

        $user = User::create([
            
            'name' => ucwords($data['name']),
            'apellido' => ucwords($data['apellido']),
            'telefono' => $data['telefono'],
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'abonado' => 'Si'
        ]);

        $user->assignRole('SuperAdmin');

        UsuarioComercio::create([
            'usuario_id' => $user->id,            
            'comercio_id' => $comercio->id            
        ]);

        return $user;

    }
}
