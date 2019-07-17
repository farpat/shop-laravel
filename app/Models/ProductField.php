<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $type
 * @property string $label
 * @property boolean $is_required
 * @property int $category_id
 * @property Category $category
 */
class ProductField extends Model
{
    protected $fillable = [
        'type', 'label', 'is_required', 'category_id'
    ];

    public function category ()
    {
        return $this->belongsTo(Category::class);
    }
}
