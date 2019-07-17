<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $module_id
 * @property string $label
 * @property string $value
 * @property string|null $description
 */
class ModuleParameter extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'module_id', 'label', 'value', 'description'
    ];
}
