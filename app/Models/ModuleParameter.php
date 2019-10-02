<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
