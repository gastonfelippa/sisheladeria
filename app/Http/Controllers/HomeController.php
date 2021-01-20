<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UsuarioComercio;

class HomeController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {     
        
        $this->middleware('auth');
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //inicializa la variable de session idComercio con el comercio asignado al usuario actual
        $userComercio = UsuarioComercio::select('comercio_id')
                      ->where('usuario_id', Auth()->user()->id)->get();
  
        session(['idComercio' => $userComercio[0]->comercio_id]);

        return view('home');
    }
}
