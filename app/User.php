<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'apellido', 'email', 'password', 'telefono1', 'telefono2','direccion','abonado'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

        /**
         * Send the password reset notification.
         * 
         * @param string token
         * @return void
         */

    public function sendPasswordResetNotification($token)
    {   
        $this->notify(new ResetPasswordNotification($token));
    }

    protected static function booted(){
        static::updated(function($user){
        });
    }
}
