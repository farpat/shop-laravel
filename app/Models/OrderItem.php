<?php

namespace App\Models;

use App\Support\StringUtility;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderItem
 *
 * @property int $id
 * @property int $cart_id
 * @property int $quantity
 * @property int|null $product_reference_id
 * @property float $amount_excluding_taxes
 * @property float $amount_including_taxes
 * @property-read \App\Models\Billing $cart
 * @property-read mixed $formatted_amount_excluding_taxes
 * @property-read mixed $formatted_amount_including_taxes
 * @property-read \App\Models\ProductReference|null $product_reference
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereAmountExcludingTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereAmountIncludingTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereProductReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereQuantity($value)
 * @mixin \Eloquent
 */
class OrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id', 'orderable_type', 'orderable_id', 'quantity', 'product_reference_id', 'amount_excluding_taxes',
        'amount_including_taxes'
    ];

    public function __construct (array $attributes = [])
    {
        $attributes = array_merge([
            'amount_excluding_taxes' => 0,
            'amount_including_taxes' => 0,
        ], $attributes);

        parent::__construct($attributes);
    }

    public function getFormattedAmountIncludingTaxesAttribute ()
    {
        return StringUtility::getFormattedPrice($this->amount_including_taxes);
    }

    public function getFormattedAmountExcludingTaxesAttribute ()
    {
        return StringUtility::getFormattedPrice($this->amount_excluding_taxes);
    }

    public function product_reference ()
    {
        return $this->belongsTo(ProductReference::class);
    }

    public function orderable ()
    {
        return $this->morphTo();
    }
}
