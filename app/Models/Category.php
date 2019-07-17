<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $label
 * @property string $nomenclature
 * @property string $slug
 * @property string $description
 * @property boolean $is_last
 * @property int|null $image_id
 * @property Image|null $image
 * @property Product[]|Collection $products
 */
class Category extends Model
{
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
}
