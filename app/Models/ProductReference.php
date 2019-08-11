<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductReference
 *
 * @property int $id
 * @property string $label
 * @property int $product_id
 * @property int|null $main_image_id
 * @property float $unit_price_excluding_taxes
 * @property array $filled_product_fields
 * @property-read mixed $unit_price_including_taxes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
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
 * @mixin \Eloquent
 */
class ProductReference extends Model
{
    public $timestamps = false;

    protected $casts = [
        'filled_product_fields'      => 'array',
        'unit_price_excluding_taxes' => 'float'
    ];

    protected $appends = ['unit_price_including_taxes'];

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

    public function getUnitPriceIncludingTaxesAttribute ()
    {
        if ($this->getAttribute('total_taxes') === null) {
            $totalTaxes = $this->product->taxes->reduce(function ($acc, Tax $tax) {
                if ($tax->type === Tax::UNITY_TYPE) {
                    $acc += $tax->value;
                } elseif ($tax->type === Tax::PERCENTAGE_TYPE) {
                    $acc += $this->unit_price_excluding_taxes * ($tax->value / 100);
                }

                return $acc;
            }, 0);

            $this->setAttribute('total_taxes', $totalTaxes);
        }

        return $this->unit_price_excluding_taxes + $this->getAttribute('total_taxes');
    }
}
