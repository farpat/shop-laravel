<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tax
 *
 * @property int $id
 * @property string $label
 * @property string $type
 * @property float $value
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tax newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tax newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tax query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tax whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tax whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tax whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tax whereValue($value)
 * @mixin \Eloquent
 */
class Tax extends Model
{
    const PERCENTAGE_TYPE = 'PERCENTAGE';
    const UNITY_TYPE = 'UNITY';

    public $timestamps = false;

    protected $fillable = [
        'label', 'type', 'value'
    ];

    public function products ()
    {
        return $this->belongsToMany(Product::class, 'products_taxes', 'tax_id', 'product_id');
    }
}
