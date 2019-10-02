<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductField extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'type', 'label', 'is_required', 'category_id'
    ];

    public function category ()
    {
        return $this->belongsTo(Category::class);
    }
}
