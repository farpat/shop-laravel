<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $items_count
 * @property float $total_amount_excluding_taxes
 * @property float $total_amount_including_taxes
 * @property string $status
 * @property string $comment
 * @property int $user_id
 * @property User $user
 * @property int|null $address_id
 * @property Address|null $address
 * @property CartItem[]|Collection $items
 */
class Cart extends Model
{
    const ORDERING_STATUS = 'ORDERING';
    const ORDERED_STATUS = 'ORDERED';
    const DELIVRED_STATUS = 'DELIVRED';

    protected $fillable = [
        'items_count', 'total_amount_excluding_taxes', 'total_amount_including_taxes', 'status', 'comment', 'user_id', 'address_id'
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
