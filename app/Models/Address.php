<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'line1', 'line2', 'postal_code', 'city', 'country', 'latitude', 'longitude', 'user_id'
    ];

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function carts ()
    {
        return $this->hasMany(Cart::class);
    }
}
