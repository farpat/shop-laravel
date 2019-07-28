<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $label
 * @property string $nomenclature
 * @property string $slug
 * @property string $description
 * @property boolean $is_last
 * @property int|null $image_id
 * @property Image|null $image
 * @property Product[]|Collection $products
 * @property-read string $url
 * @property-read string $meta_description
 * @property-read int $level
 */
class Category extends Model
{
    const PRODUCTS_PER_PAGE = 2;
    const BREAKING_POINT = '.';

    public $timestamps = false;

    protected $fillable = [
        'label', 'nomenclature', 'slug', 'description', 'is_last', 'image_id'
    ];

    public function products ()
    {
        $this->hasMany(Product::class);
    }

    public function image ()
    {
        $this->hasOne(Image::class);
    }

    public function getLevelAttribute ()
    {
        return substr_count($this->nomenclature, Category::BREAKING_POINT) + 1;
    }

    public function getUrlAttribute ()
    {
        return route('categories.show', ['category' => $this->id, 'slug' => $this->slug]);
    }

    public function getMetaDescriptionAttribute ()
    {
        return Str::limit($this->description);
    }
}
