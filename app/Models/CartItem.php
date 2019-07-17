<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $cart_id
 * @property int $quantity
 * @property int $product_reference_id
 * @property ProductReference $product_reference
 * @property float $amount_excluding_taxes
 * @property float $amount_including_taxes
 *
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
