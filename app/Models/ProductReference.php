<?php

namespace App\Models;

use App\Services\Bank\StringUtility;
use Illuminate\Database\Eloquent\Model;

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
