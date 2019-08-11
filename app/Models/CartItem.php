<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CartItem
 *
 * @property int $id
 * @property int $cart_id
 * @property int $quantity
 * @property int $product_reference_id
 * @property float $amount_excluding_taxes
 * @property float $amount_including_taxes
 * @property-read \App\Models\ProductReference $product_reference
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereAmountExcludingTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereAmountIncludingTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereProductReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereQuantity($value)
 * @mixin \Eloquent
 */
class CartItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id', 'cart_id', 'quantity', 'product_reference_id', 'amount_excluding_taxes', 'amount_including_taxes'
    ];

    public function product_reference() {
        return $this->belongsTo(ProductReference::class);
    }
}
