<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $product_id
 * @property string $label
 * @property int $main_image_id
 * @property Product $product
 * @property Image[] $image
 * @property Image $main_image
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
        'product_id', 'label', 'unit_price_excluding_taxes', 'filled_product_fields', 'main_image_id'
    ];

    public function product ()
    {
        return $this->belongsTo(Product::class);
    }

    public function images() {
        return $this->belongsToMany(Image::class, 'product_references_images');
    }

    public function main_image ()
    {
        return $this->belongsTo(Image::class, 'main_image_id');
    }
}
