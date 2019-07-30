<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $label
 * @property string $slug
 * @property string|null $excerpt
 * @property string|null $description
 * @property int $category_id
 * @property Category|null $category
 * @property Tag[]|Collection $tags
 * @property Tax[]|Collection $taxes
 * @property ProductReference[]|Collection $references
 * @property-read string $url
 */
class Product extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'label', 'slug', 'excerpt', 'description', 'category_id'
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
        return $this->belongsToMany(Tax::class, 'products_taxes');
    }

    public function references ()
    {
        return $this->hasMany(ProductReference::class);
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
