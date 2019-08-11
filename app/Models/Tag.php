<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tag
 *
 * @property int $id
 * @property string $label
 * @property string $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag whereType($value)
 * @mixin \Eloquent
 */
class Tag extends Model
{
    protected $fillable = [
        'label'
    ];

    public function products ()
    {
        $this->belongsToMany(Product::class);
    }
}
