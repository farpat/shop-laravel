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
 * @property-read float $unit_price_including_taxes
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
