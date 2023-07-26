<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'value' => 'array',
    ];

    public $timestamps = false;

    // booted
    protected static function booted(): void
    {
        static::saved(function() {
            session()->forget('user_settings');
        });
    }

    // get user for settings
    public function user(): BelongsTo
    {
        return $this->belongsTo(model('user'));
    }

    // initialize
    public function initialize($user): void
    {
        foreach ([
            'timezone' => config('atom.timezone'),
            'locale' => head(config('atom.locales')),
        ] as $key => $val) {
            model('user_setting')->create([
                'name' => $key,
                'value' => $val,
                'user_id' => $user->id,
            ]);
        }
    }
}
