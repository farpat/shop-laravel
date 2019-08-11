<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Address
 *
 * @property int $id
 * @property string $line1
 * @property string|null $line2
 * @property string $postal_code
 * @property string $city
 * @property string $country
 * @property float $latitude
 * @property float $longitude
 * @property int $user_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cart[] $carts
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereUserId($value)
 * @mixin \Eloquent
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
