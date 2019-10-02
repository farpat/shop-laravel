<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    public $timestamps = [
        'created_at'
    ];

    protected $fillable = [
        'ip_address', 'user_id', 'visitable_type', 'visitable_id'
    ];

    public function visitable ()
    {
        return $this->morphTo();
    }

    public function user ()
    {
        $this->belongsTo(User::class);
    }
}
