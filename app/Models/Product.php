<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $label
 * @property string $slug
 * @property string $excerpt
 * @property string $description
 * @property int $category_id
 * @property Category|null $category
 * @property Tag[] $tags
 * @property Tax[] $taxes
 * @property-read string $url
 */
class Product extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'label', 'slug', 'excerpt', 'description', 'category_id'
    ];

    public function category ()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags ()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function taxes ()
    {
        return $this->belongsToMany(Tax::class);
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
