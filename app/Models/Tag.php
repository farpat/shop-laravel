<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $label
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
