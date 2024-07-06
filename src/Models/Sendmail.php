<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasUlid;

class Sendmail extends Model
{
    use HasFactory;
    use HasFilters;
    use HasUlid;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'status' => \Jiannius\Atom\Enums\Sendmail\Status::class,
    ];

    protected static function booted() : void
    {
        static::saving(function($sendmail) {
            $sendmail->fill([
                'user_id' => user('id'),
            ]);
        });
    }

    // get user for sendmail
    public function user() : BelongsTo
    {
        return $this->belongsTo(model('user'));
    }

    // scope for search
    public function scopeSearch($query, $search) : void
    {
        $query->whereAny(['ulid', 'subject'], 'like', "%$search%");
    }
}
