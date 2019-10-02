<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
