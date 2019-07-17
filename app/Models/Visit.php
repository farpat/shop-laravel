<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $ip_address
 * @property int $user_id
 * @property User|null $user
 * @property string $visible_type
 * @property int $visible_id
 * @property Product|Category $visitable
 */
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
