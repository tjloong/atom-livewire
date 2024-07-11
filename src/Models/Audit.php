<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Audit extends Model
{
    use HasFactory;
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'request' => 'array',
        'tags' => 'array',
        'event' => \App\Enums\Audit\Event::class,
    ];

    // booted
    protected static function booted() : void
    {
        static::creating(function($audit) {
            $audit->fill([
                'request' => [
                    'ip' => request()->ip(),
                    'user_agent' => request()->header('user-agent'),
                ],
                'user_id' => user('id'),
            ]);
        });
    }

    // get auditable for trace
    public function auditable() : MorphTo
    {
        return $this->morphTo();
    }

    // get user for trace
    public function user() : BelongsTo
    {
        return $this->belongsTo(model('user'));
    }

    // scope for fussy search
    public function scopeSearch($query, $search) : void
    {
        $query->where(fn($q) => $q
            ->where('event', 'like', "%$search%")
            ->orWhere('request->ip', 'like', "%$search%")
            ->orWhere('tags', 'like', "%$search%")
        );
    }
}
