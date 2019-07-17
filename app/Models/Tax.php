<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $label
 * @property string $type
 * @property float $value
 */
class Tax extends Model
{
    const PERCENTAGE_TYPE = '%';
    const UNITY_TYPE = 'UNITY';

    public $timestamps = false;

    protected $fillable = [
        'label', 'type', 'value'
    ];

    public function products ()
    {
        $this->belongsToMany(Product::class);
    }
}
