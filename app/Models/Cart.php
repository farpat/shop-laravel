<?php

namespace App\Models;

use App\Services\Bank\StringUtility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Cart
 *
 * @property int $id
 * @property string|null $number
 * @property int $items_count
 * @property float $total_amount_excluding_taxes
 * @property float $total_amount_including_taxes
 * @property string $status
 * @property string|null $comment
 * @property int $user_id
 * @property string $user_name
 * @property string $user_email
 * @property int|null $address_id
 * @property string|null $address_text
 * @property string|null $address_line1
 * @property string|null $address_line2
 * @property string|null $address_postal_code
 * @property string|null $address_city
 * @property string|null $address_country
 * @property float|null $address_latitude
 * @property float|null $address_longitude
 * @property int|null $address_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Address|null $address
 * @property-read mixed $billing_path
 * @property-read mixed $formatted_including_taxes
 * @property-read mixed $formatted_total_amount_excluding_taxes
 * @property-read mixed $formatted_total_amount_including_taxes
 * @property-read mixed $including_taxes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CartItem[] $items
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereAddressCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereAddressCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereAddressLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereAddressLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereAddressPostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereAddressText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereAddressUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereItemsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereTotalAmountExcludingTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereTotalAmountIncludingTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserName($value)
 * @mixin \Eloquent
 */
class Cart extends Model
{
    const ORDERING_STATUS = 'ORDERING';
    const ORDERED_STATUS = 'ORDERED';
    const DELIVRED_STATUS = 'DELIVRED';

    protected $fillable = [
        'items_count', 'total_amount_excluding_taxes', 'total_amount_including_taxes', 'status', 'comment', 'number',

        //user informations
        'user_id', 'user_name', 'user_email',

        //address informations
        'address_id', 'address_text', 'address_line1', 'address_line2', 'address_postal_code', 'address_city',
        'address_country', 'address_latitude', 'address_longitude'
    ];

    protected $casts = [
        'total_amount_excluding_taxes' => 'float',
        'total_amount_including_taxes' => 'float',
    ];

    public function getFormattedTotalAmountIncludingTaxesAttribute ()
    {
        return StringUtility::getFormattedPrice($this->total_amount_including_taxes);
    }

    public function getFormattedTotalAmountExcludingTaxesAttribute ()
    {
        return StringUtility::getFormattedPrice($this->total_amount_excluding_taxes);
    }

    public function getIncludingTaxesAttribute ()
    {
        return $this->total_amount_including_taxes - $this->total_amount_excluding_taxes;
    }

    public function getFormattedIncludingTaxesAttribute ()
    {
        return StringUtility::getFormattedPrice($this->including_taxes);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName ()
    {
        return 'number';
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

    public function getBillingPathAttribute ()
    {
        return Storage::disk('private')->path("billings/{$this->user->id}/{$this->number}.pdf");
    }

    public function computeNextNumber (string $nextNumber): string
    {
        return $this->updated_at->format('Y-m') . '-' . $nextNumber;
    }
}
