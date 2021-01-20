<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Mail\Contact;

class EnviarContraseña
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
       // var_dump('Su contraseña es '. $event->contraseña . ' Sr '. $event->user);

        Mail::to('nuestroEmail@gmail.com')->queue(
            new Contact($event->user, $event->contraseña, $event->salutation));
        
        return redirect()->route('contactanos.index')->with('info','Correo enviado...\nPronto recibirás una respuesta!!!');

        // Mail::send('emails.contact', $event, function ($message) {
        //     $message->from('tucorreo@gmail.com','Registro con exito');
        //     $message->to('usuario@gmail.com')->subject('Hay un nuevo registro');
        // });
    }
}
