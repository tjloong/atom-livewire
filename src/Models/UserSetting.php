<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'value' => 'array',
        'user_id' => 'integer',
    ];

    // get user for settings
    public function user(): BelongsTo
    {
        return $this->belongsTo(model('user'));
    }

    // initialize
    public function initialize($userId): void
    {
        foreach ([
            'timezone' => config('atom.timezone'),
            'locale' => head(config('atom.locales')),
        ] as $key => $val) {
            model('user_setting')->create([
                'name' => $key,
                'value' => $val,
                'user_id' => $userId,
            ]);
        }

    }
}
