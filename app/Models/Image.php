<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Image
 *
 * @property int $id
 * @property string $url
 * @property string $alt
 * @property string|null $url_thumbnail
 * @property string|null $alt_thumbnail
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereAltThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereUrlThumbnail($value)
 * @mixin \Eloquent
 */
class Image extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'url', 'alt', 'url_thumbnail', 'alt_thumbnail'
    ];
}
