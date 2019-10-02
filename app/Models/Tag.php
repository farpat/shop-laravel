<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
