<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $url
 * @property string $alt
 * @property string $url_thumbnail
 * @property string $alt_thumbnail
 */
class Image extends Model
{
    public $timestamps = [
        'created_at'
    ];

    protected $fillable = [
        'url', 'alt', 'url_thumbnail', 'alt_thumbnail'
    ];
}
