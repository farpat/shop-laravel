<?php

namespace App\Models;

use App\Support\StringUtility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderItem[] $items
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereAddressCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereAddressCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereAddressLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereAddressLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereAddressPostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereAddressText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereAddressUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereItemsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereTotalAmountExcludingTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereTotalAmountIncludingTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereUserEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Billing whereUserName($value)
 * @mixin \Eloquent
 */
class Billing extends Model
{
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

    public function getBillingPathAttribute ()
    {
        return Storage::disk('private')->path("billings/{$this->user->id}/{$this->number}.pdf");
    }

    public static function computeNextNumber (string $prefix, string $nextNumber): string
    {
        return $prefix . '-' . $nextNumber;
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
        return $this->morphMany(OrderItem::class, 'orderable');
    }
}
