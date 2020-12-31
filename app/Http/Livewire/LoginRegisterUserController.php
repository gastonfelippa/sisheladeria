<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\User;


class LoginRegisterUserController extends Component
{
    public $vista='1';
    
    public function render()
    {
        
        if($this->vista == '1')
        return view ('auth.login');
        else
        return view ('auth.register');

        // $user = User::all();
        // if($user->count() > 0)
        // return view ('auth.login');
        // else
        // return view ('auth.register');
    }
    public function CambiarVista()
    {
      //  dd($vista);
        $this->vista = '2';
    }
    public function doAction($action)
	{

        $this->vista = $action;
       // dd($this->vista);
    }

}