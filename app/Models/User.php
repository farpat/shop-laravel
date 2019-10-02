<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password', 'stripe_id', 'selected_address_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function addresses ()
    {
        return $this->hasMany(Address::class);
    }

    public function selected_address ()
    {
        return $this->hasOne(Address::class);
    }

    public function visits ()
    {
        return $this->hasMany(Visit::class);
    }
}
