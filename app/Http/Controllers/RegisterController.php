<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Events\UserRegistered; #Importar el evento
use App\Mail\RegistrarseMailable;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public $data, $contraseña;

    public function index() {
        
        return view('emails.registrarse');
    }
    
    public function store() {  
        // var_dump('Enviar mensaje'); 

        // $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $str = "ABCDEFGHIJKLMNOP12345678901234567890";
        $contraseña = "";
        //Reconstruimos la contraseña segun la longitud que se quiera
        for($i=0;$i<8;$i++) {
            //obtenemos un caracter aleatorio escogido de la cadena de caracteres
            $contraseña .= substr($str,rand(0,36),1);
        }
        $data = array(
            'clave' => $contraseña,
        );
        UserRegistered::dispatch('Gastón', $data, 'Saludos cordiales!!');
        
    }
}

            