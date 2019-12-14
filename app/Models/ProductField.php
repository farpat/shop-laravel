<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\ProductField
 *
 * @property int $id
 * @property string $type
 * @property string $label
 * @property int $is_required
 * @property int $category_id
 * @property-read \App\Models\Category $category
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductField query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductField whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductField whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductField whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductField whereType($value)
 * @mixin \Eloquent
 */
class ProductField extends Model
{
    public $timestamps = false;

    protected $appends = ['snake_label'];

    protected $fillable = [
        'type', 'label', 'is_required', 'category_id'
    ];

    public function category ()
    {
        return $this->belongsTo(Category::class);
    }

    public function getSnakeLabelAttribute ()
    {
        return Str::kebab($this->label);
    }
}
