<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ModuleParameter
 *
 * @property int $id
 * @property int $module_id
 * @property string $label
 * @property string|array $value
 * @property string|null $description
 * @property-read \App\Models\Module $module
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModuleParameter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModuleParameter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModuleParameter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModuleParameter whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModuleParameter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModuleParameter whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModuleParameter whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModuleParameter whereValue($value)
 * @mixin \Eloquent
 */
class ModuleParameter extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'module_id', 'label', 'value', 'description'
    ];

    public function module ()
    {
        return $this->belongsTo(Module::class);
    }
}
