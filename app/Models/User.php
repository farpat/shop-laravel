<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Laravel\Passport\HasApiTokens;

/**
 * @property-read int id
 * @property string name
 * @property string email
 * @property string password
 * @property Carbon email_verified_at
 * @property Address[]|Collection $addresses
 * @property Address|null $selected_address_id
 * @property Visit[]|Collection $visits
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password',
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
