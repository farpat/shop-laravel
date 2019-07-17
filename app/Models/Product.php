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
 */
class Product extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'label', 'slug', 'excerpt', 'description', 'category_id'
    ];

    public function tags ()
    {
        $this->belongsToMany(Tag::class);
    }

    public function taxes ()
    {
        $this->belongsToMany(Tax::class);
    }
}
