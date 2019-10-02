<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'label', 'description', 'is_active'
    ];

    public function parameters ()
    {
        return $this->hasMany(ModuleParameter::class);
    }
}
