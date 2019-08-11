<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Visit
 *
 * @property int $id
 * @property string $ip_address
 * @property int|null $user_id
 * @property string $visitable_type
 * @property int $visitable_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $visitable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Visit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Visit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Visit query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Visit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Visit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Visit whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Visit whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Visit whereVisitableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Visit whereVisitableType($value)
 * @mixin \Eloquent
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
