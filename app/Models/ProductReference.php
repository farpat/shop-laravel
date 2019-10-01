<?php

namespace App\Models;

use App\Services\Bank\HasPriceAttributes;
use App\Services\Bank\StringUtility;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductReference
 *
 * @property int $id
 * @property string $label
 * @property int $product_id
 * @property int|null $main_image_id
 * @property float $unit_price_excluding_taxes
 * @property float $unit_price_including_taxes
 * @property array $filled_product_fields
 * @property-read mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 * @property-read int|null $images_count
 * @property-read \App\Models\Image|null $main_image
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductReference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductReference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductReference query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductReference whereFilledProductFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductReference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductReference whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductReference whereMainImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductReference whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductReference whereUnitPriceExcludingTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductReference whereUnitPriceIncludingTaxes($value)
 * @mixin \Eloquent
 */
class ProductReference extends Model
{
    public $timestamps = false;

    protected $casts = [
        'filled_product_fields'      => 'array',
        'unit_price_excluding_taxes' => 'float',
        'unit_price_including_taxes' => 'float',
    ];

    protected $appends = ['url'];

    protected $fillable = [
        'product_id', 'label', 'unit_price_excluding_taxes', 'unit_price_including_taxes', 'filled_product_fields', 'main_image_id'
    ];

    public function getFormattedUnitPriceExcludingTaxesAttribute ()
    {
        return StringUtility::getFormattedPrice($this->unit_price_excluding_taxes);
    }

    public function getFormattedUnitPriceIncludingTaxesAttribute ()
    {
        return StringUtility::getFormattedPrice($this->unit_price_including_taxes);
    }

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

    public function getUrlAttribute ()
    {
        return $this->product->url;
    }
}
