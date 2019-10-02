<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'url', 'alt', 'url_thumbnail', 'alt_thumbnail'
    ];
}
