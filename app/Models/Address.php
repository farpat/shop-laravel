<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $line1
 * @property string|null $line2
 * @property string $postal_code
 * @property string $city
 * @property string $country
 * @property float $latitude
 * @property float $longitude
 * @property int $user_id
 * @property User $user
 */
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
