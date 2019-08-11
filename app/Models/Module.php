<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Module
 *
 * @property int $id
 * @property string $label
 * @property string|null $description
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ModuleParameter[] $parameters
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Module newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Module newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Module onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Module query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Module whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Module whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Module whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Module whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Module whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Module withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Module withoutTrashed()
 * @mixin \Eloquent
 */
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
