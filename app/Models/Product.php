<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'label', 'slug', 'excerpt', 'description', 'category_id', 'main_image_id',
    ];

    protected $appends = ['url'];

    public function category ()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags ()
    {
        return $this->belongsToMany(Tag::class, 'products_tags');
    }

    public function taxes ()
    {
        return $this->belongsToMany(Tax::class, 'products_taxes', 'product_id', 'tax_id');
    }

    public function references ()
    {
        return $this->hasMany(ProductReference::class);
    }

    public function main_image() {
        return $this->belongsTo(Image::class, 'main_image_id');
    }

    public function getUrlAttribute ()
    {
        return route('products.show', [
            'categorySlug' => $this->category->slug,
            'categoryId'   => $this->category,
            'slug'         => $this->slug,
            'product'      => $this
        ]);
    }

    public function getMetaDescriptionAttribute ()
    {
        return $this->excerpt;
    }
}
