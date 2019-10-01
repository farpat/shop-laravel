<?php

namespace App\Models;

use App\Services\Bank\StringUtility;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CartItem
 *
 * @property int $id
 * @property int $cart_id
 * @property int $quantity
 * @property int|null $product_reference_id
 * @property float $amount_excluding_taxes
 * @property float $amount_including_taxes
 * @property-read Cart $cart
 * @property-read ProductReference|null $product_reference
 * @method static Builder|CartItem newModelQuery()
 * @method static Builder|CartItem newQuery()
 * @method static Builder|CartItem query()
 * @method static Builder|CartItem whereAmountExcludingTaxes($value)
 * @method static Builder|CartItem whereAmountIncludingTaxes($value)
 * @method static Builder|CartItem whereCartId($value)
 * @method static Builder|CartItem whereId($value)
 * @method static Builder|CartItem whereProductReferenceId($value)
 * @method static Builder|CartItem whereQuantity($value)
 * @mixin Eloquent
 */
class CartItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id', 'cart_id', 'quantity', 'product_reference_id', 'amount_excluding_taxes', 'amount_including_taxes'
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

    public function cart ()
    {
        return $this->belongsTo(Cart::class);
    }
}
