<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\User;


class LoginRegisterUserController extends Component
{
    
    public function render()
    {
        // $user = User::all();
        // if($user->count() > 0)
        // return view ('auth.login');
        // else
        return view ('auth.register');

    }

}