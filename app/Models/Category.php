<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $label
 * @property string $nomenclature
 * @property string $slug
 * @property string $description
 * @property int $is_last
 * @property int|null $image_id
 * @property-read mixed $level
 * @property-read mixed $meta_description
 * @property-read mixed $url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereIsLast($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereNomenclature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereSlug($value)
 * @mixin \Eloquent
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
