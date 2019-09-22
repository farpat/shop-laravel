<?php

namespace App\Models;

use App\Services\Bank\StringUtility;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * App\Models\Cart
 *
 * @property int $id
 * @property int|null $items_count
 * @property float $total_amount_excluding_taxes
 * @property float $total_amount_including_taxes
 * @property string $status
 * @property string|null $comment
 * @property int $user_id
 * @property int|null $address_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Address|null $address
 * @property-read Collection|CartItem[] $items
 * @property-read User $user
 * @method static Builder|Cart newModelQuery()
 * @method static Builder|Cart newQuery()
 * @method static Builder|Cart query()
 * @method static Builder|Cart whereAddressId($value)
 * @method static Builder|Cart whereComment($value)
 * @method static Builder|Cart whereCreatedAt($value)
 * @method static Builder|Cart whereId($value)
 * @method static Builder|Cart whereItemsCount($value)
 * @method static Builder|Cart whereStatus($value)
 * @method static Builder|Cart whereTotalAmountExcludingTaxes($value)
 * @method static Builder|Cart whereTotalAmountIncludingTaxes($value)
 * @method static Builder|Cart whereUpdatedAt($value)
 * @method static Builder|Cart whereUserId($value)
 * @mixin Eloquent
 */
class Cart extends Model
{
    const ORDERING_STATUS = 'ORDERING';
    const ORDERED_STATUS = 'ORDERED';
    const DELIVRED_STATUS = 'DELIVRED';

    protected $fillable = [
        'items_count', 'total_amount_excluding_taxes', 'total_amount_including_taxes', 'status', 'comment', 'user_id',
        'address_id', 'number'
    ];

    protected $casts = [
        'total_amount_excluding_taxes' => 'float',
        'total_amount_including_taxes' => 'float',
    ];

    public function getFormattedTotalAmountIncludingTaxesAttribute ()
    {
        return StringUtility::getFormattedPrice($this->total_amount_including_taxes);
    }

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
