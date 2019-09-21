<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cart
 *
 * @property int $id
 * @property-read int|null $items_count
 * @property float $total_amount_excluding_taxes
 * @property float $total_amount_including_taxes
 * @property string $status
 * @property string|null $comment
 * @property int $user_id
 * @property int|null $address_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Address|null $address
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CartItem[] $items
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereItemsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereTotalAmountExcludingTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereTotalAmountIncludingTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserId($value)
 * @mixin \Eloquent
 */
class Cart extends Model
{
    const ORDERING_STATUS = 'ORDERING';
    const ORDERED_STATUS = 'ORDERED';
    const DELIVRED_STATUS = 'DELIVRED';

    protected $fillable = [
        'items_count', 'total_amount_excluding_taxes', 'total_amount_including_taxes', 'status', 'comment', 'user_id', 'address_id'
    ];

    protected $casts = [
        '$total_amount_excluding_taxes' => 'float',
        '$total_amount_including_taxes' => 'float',
    ];

    public function address ()
    {
        return $this->belongsTo(Address::class);
    }

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function items ()
    {
        return $this->hasMany(CartItem::class);
    }
}
