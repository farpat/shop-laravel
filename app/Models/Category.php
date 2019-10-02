<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    const PRODUCTS_PER_PAGE = 8;
    const BREAKING_POINT = '.';

    public $timestamps = false;

    protected $appends = ['url'];

    protected $fillable = [
        'label', 'nomenclature', 'slug', 'description', 'is_last', 'image_id'
    ];

    public function products ()
    {
        return $this->hasMany(Product::class);
    }

    public function image ()
    {
        return $this->belongsTo(Image::class);
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
