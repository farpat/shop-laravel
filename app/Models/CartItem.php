<?php

namespace App\Models;

use App\Services\Bank\StringUtility;
use Illuminate\Database\Eloquent\Model;

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
