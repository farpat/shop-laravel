<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $label
 * @property string $slug
 * @property int|null $category_id
 * @property int|null $main_image_id
 * @property string|null $excerpt
 * @property string|null $description
 * @property-read \App\Models\Category|null $category
 * @property-read mixed $meta_description
 * @property-read mixed $url
 * @property-read \App\Models\Image|null $main_image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductReference[] $references
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tax[] $taxes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereMainImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereSlug($value)
 * @mixin \Eloquent
 */
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
            'category'     => $this->category,
            'id'           => $this->id,
            'slug'         => $this->slug
        ]);
    }

    public function getMetaDescriptionAttribute ()
    {
        return $this->excerpt;
    }
}
