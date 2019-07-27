<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $product_id
 * @property Product $product
 * @property float $unit_price_excluding_taxes
 * @property array $filled_product_fields
 */
class ProductReference extends Model
{
    public $timestamps = false;

    protected $casts = [
        'filled_product_fields' => 'array',
    ];

    protected $fillable = [
        'product_id', 'unit_price_excluding_taxes', 'filled_product_fields'
    ];

    public function product ()
    {
        return $this->belongsTo(Product::class);
    }

    public function images() {
        return $this->belongsToMany(Image::class, 'product_references_images');
    }
}
